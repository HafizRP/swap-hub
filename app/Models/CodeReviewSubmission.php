<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodeReviewSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_review_request_id',
        'reviewer_id',
        'feedback',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(CodeReviewRequest::class, 'code_review_request_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
