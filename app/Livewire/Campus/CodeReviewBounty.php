<?php

declare(strict_types=1);

namespace App\Livewire\Campus;

use App\Models\CodeReviewRequest;
use App\Models\CodeReviewSubmission;
use App\Models\User;
use App\Services\CodeReviewService;
use Livewire\Component;
use Livewire\WithPagination;

class CodeReviewBounty extends Component
{
    use WithPagination;

    // Create Request
    public string $title = '';

    public string $description = '';

    public string $repositoryUrl = '';

    public string $pullRequestUrl = '';

    public int $bountyCredits = 10;

    // Active Selected Request for Feedback Submission
    public ?int $activeRequestId = null;

    public string $feedback = '';

    public function createRequest(CodeReviewService $codeReviewService): void
    {
        /** @var User $user */
        $user = User::findOrFail(auth()->id());

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'repositoryUrl' => 'required|url',
            'pullRequestUrl' => 'nullable|url',
            'bountyCredits' => 'required|integer|min:0|max:'.$user->credits,
        ], [
            'bountyCredits.max' => 'Saldo kredit Anda tidak mencukupi untuk bounty ini.',
        ]);

        try {
            $codeReviewService->createRequest($user, [
                'title' => trim($this->title),
                'description' => $this->description ? trim($this->description) : null,
                'repository_url' => $this->repositoryUrl,
                'pr_url' => $this->pullRequestUrl,
                'bounty_credits' => $this->bountyCredits,
            ]);
        } catch (\Throwable $e) {
            $this->addError('bountyCredits', $e->getMessage());

            return;
        }

        $this->reset(['title', 'description', 'repositoryUrl', 'pullRequestUrl', 'bountyCredits']);
        session()->flash('message', 'Permintaan Code Review berhasil dibuat!');
    }

    public function selectRequest(int $requestId): void
    {
        $this->activeRequestId = $requestId;
        $this->reset('feedback');
    }

    public function submitFeedback(CodeReviewService $codeReviewService): void
    {
        $this->validate([
            'feedback' => 'required|string|min:10',
        ]);

        $request = CodeReviewRequest::findOrFail($this->activeRequestId);

        if ($request->user_id === auth()->id()) {
            $this->addError('feedback', 'Anda tidak dapat memberikan review untuk kode milik sendiri.');

            return;
        }

        /** @var User $reviewer */
        $reviewer = User::findOrFail(auth()->id());
        $codeReviewService->submitReview($reviewer, $request, trim($this->feedback));

        $this->reset(['feedback', 'activeRequestId']);
        session()->flash('message', 'Review feedback berhasil dikirim!');
    }

    public function acceptSubmission(int $submissionId, CodeReviewService $codeReviewService): void
    {
        $submission = CodeReviewSubmission::with('request')->findOrFail($submissionId);

        if ($submission->request->user_id !== auth()->id()) {
            abort(403, 'Hanya pembuat request yang dapat menerima submission.');
        }

        try {
            if ($submission->request->status === 'completed') {
                throw new \Throwable('Request sudah selesai, tidak dapat menerima submission baru.');
            }
            $codeReviewService->acceptReview($submission);
            session()->flash('message', 'Review diterima dan bounty berhasil diberikan ke reviewer!');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $user = User::findOrFail(auth()->id());

        $openRequests = CodeReviewRequest::with(['user', 'submissions.reviewer'])
            ->latest()
            ->paginate(10);

        return view('livewire.campus.code-review-bounty', [
            'openRequests' => $openRequests,
            'user' => $user,
        ])->layout('layouts.app');
    }
}
