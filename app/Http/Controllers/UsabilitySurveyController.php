<?php

namespace App\Http\Controllers;

use App\Models\UsabilityFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsabilitySurveyController extends Controller
{
    /**
     * Show the 10-item System Usability Scale (SUS) survey form.
     */
    public function create(): View
    {
        $questions = [
            1 => 'Saya rasa saya akan sering menggunakan Swap Hub.',
            2 => 'Saya merasa sistem ini terlalu rumit untuk digunakan.',
            3 => 'Saya merasa sistem ini mudah digunakan.',
            4 => 'Saya membutuhkan bantuan orang teknis untuk bisa menggunakan sistem ini.',
            5 => 'Saya merasa berbagai fungsi dalam sistem ini terintegrasi dengan baik.',
            6 => 'Saya merasa banyak hal yang tidak konsisten pada sistem ini.',
            7 => 'Saya rasa kebanyakan orang akan cepat belajar menggunakan sistem ini.',
            8 => 'Saya merasa sistem ini membingungkan saat digunakan.',
            9 => 'Saya merasa sangat percaya diri saat menggunakan sistem ini.',
            10 => 'Saya perlu membiasakan diri terlebih dahulu sebelum bisa menggunakan sistem ini.',
        ];

        return view('survey.sus', compact('questions'));
    }

    /**
     * Store the submitted SUS questionnaire responses and calculate the score.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'respondent_name' => 'nullable|string|max:100',
            'respondent_role' => 'required|string|max:100',
            'q1' => 'required|integer|between:1,5',
            'q2' => 'required|integer|between:1,5',
            'q3' => 'required|integer|between:1,5',
            'q4' => 'required|integer|between:1,5',
            'q5' => 'required|integer|between:1,5',
            'q6' => 'required|integer|between:1,5',
            'q7' => 'required|integer|between:1,5',
            'q8' => 'required|integer|between:1,5',
            'q9' => 'required|integer|between:1,5',
            'q10' => 'required|integer|between:1,5',
            'qualitative_feedback' => 'nullable|string|max:1000',
        ]);

        $score = UsabilityFeedback::calculateScore($validated);
        $grade = UsabilityFeedback::determineGrade($score);
        $adjective = UsabilityFeedback::determineAdjective($score);

        UsabilityFeedback::create([
            'user_id' => Auth::id(),
            'respondent_name' => $validated['respondent_name'] ?? (Auth::user()?->name ?? 'Mahasiswa Responden'),
            'respondent_role' => $validated['respondent_role'],
            'q1' => $validated['q1'],
            'q2' => $validated['q2'],
            'q3' => $validated['q3'],
            'q4' => $validated['q4'],
            'q5' => $validated['q5'],
            'q6' => $validated['q6'],
            'q7' => $validated['q7'],
            'q8' => $validated['q8'],
            'q9' => $validated['q9'],
            'q10' => $validated['q10'],
            'sus_score' => $score,
            'grade' => $grade,
            'adjective_rating' => $adjective,
            'qualitative_feedback' => $validated['qualitative_feedback'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('status', 'sus-submitted')->with('sus_score', $score);
    }

    /**
     * Show Academic System Usability Scale (SUS) evaluation report in Admin Panel.
     */
    public function adminReport(): View
    {
        $feedbacks = UsabilityFeedback::latest()->get();
        $totalRespondents = $feedbacks->count();

        $stats = [
            'total' => $totalRespondents,
            'mean_score' => $totalRespondents > 0 ? round($feedbacks->avg('sus_score'), 2) : 0,
            'min_score' => $totalRespondents > 0 ? $feedbacks->min('sus_score') : 0,
            'max_score' => $totalRespondents > 0 ? $feedbacks->max('sus_score') : 0,
            'grade' => $totalRespondents > 0 ? UsabilityFeedback::determineGrade($feedbacks->avg('sus_score')) : 'N/A',
            'adjective' => $totalRespondents > 0 ? UsabilityFeedback::determineAdjective($feedbacks->avg('sus_score')) : 'N/A',
        ];

        // Compute standard deviation
        $variance = 0.0;
        if ($totalRespondents > 1) {
            $sumDiff = 0.0;
            foreach ($feedbacks as $fb) {
                $sumDiff += pow($fb->sus_score - $stats['mean_score'], 2);
            }
            $variance = $sumDiff / ($totalRespondents - 1);
        }
        $stats['std_dev'] = round(sqrt($variance), 2);

        // Question by question averages
        $questionAverages = [];
        for ($i = 1; $i <= 10; $i++) {
            $questionAverages["q{$i}"] = $totalRespondents > 0 ? round($feedbacks->avg("q{$i}"), 2) : 0;
        }

        return view('admin.usability', compact('feedbacks', 'stats', 'questionAverages'));
    }
}
