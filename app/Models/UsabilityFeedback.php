<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsabilityFeedback extends Model
{
    use HasFactory;

    protected $table = 'usability_feedbacks';

    protected $guarded = ['id'];

    protected $casts = [
        'q1' => 'integer',
        'q2' => 'integer',
        'q3' => 'integer',
        'q4' => 'integer',
        'q5' => 'integer',
        'q6' => 'integer',
        'q7' => 'integer',
        'q8' => 'integer',
        'q9' => 'integer',
        'q10' => 'integer',
        'sus_score' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Compute System Usability Scale (SUS) Score based on Brooke (1996) formula.
     * Odd questions (1, 3, 5, 7, 9): score - 1
     * Even questions (2, 4, 6, 8, 10): 5 - score
     * Total = sum * 2.5 (Range 0 - 100)
     */
    public static function calculateScore(array $answers): float
    {
        $oddSum = 0;
        $evenSum = 0;

        foreach ([1, 3, 5, 7, 9] as $i) {
            $val = (int) ($answers["q{$i}"] ?? 3);
            $oddSum += ($val - 1);
        }

        foreach ([2, 4, 6, 8, 10] as $i) {
            $val = (int) ($answers["q{$i}"] ?? 3);
            $evenSum += (5 - $val);
        }

        $totalScore = ($oddSum + $evenSum) * 2.5;

        return round(max(0, min(100, $totalScore)), 2);
    }

    /**
     * Determine Grade Scale according to Sauro & Lewis (2016).
     */
    public static function determineGrade(float $score): string
    {
        return match (true) {
            $score >= 84.1 => 'A+',
            $score >= 80.3 => 'A',
            $score >= 77.2 => 'B+',
            $score >= 74.1 => 'B',
            $score >= 68.0 => 'C', // Benchmark average is 68.0
            $score >= 51.0 => 'D',
            default => 'F',
        };
    }

    /**
     * Determine Adjective Rating according to Bangor, Kortum & Miller (2008).
     */
    public static function determineAdjective(float $score): string
    {
        return match (true) {
            $score >= 85.0 => 'Best Imaginable',
            $score >= 73.0 => 'Excellent',
            $score >= 68.0 => 'Good',
            $score >= 52.0 => 'OK',
            $score >= 38.0 => 'Poor',
            default => 'Worst Imaginable',
        };
    }
}
