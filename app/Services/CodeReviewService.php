<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CodeReviewRequest;
use App\Models\CodeReviewSubmission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CodeReviewService
{
    public function __construct(
        protected CreditLedgerService $creditLedgerService
    ) {}

    /**
     * Create a code review request and deduct bounty credits if specified.
     */
    public function createRequest(User $user, array $data): CodeReviewRequest
    {
        return DB::transaction(function () use ($user, $data) {
            $bounty = (int) ($data['bounty_credits'] ?? 0);

            if ($bounty > 0) {
                $this->creditLedgerService->spendCredits(
                    $user,
                    $bounty,
                    'Bounty for code review request: '.($data['title'] ?? 'Request'),
                );
            }

            return CodeReviewRequest::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'repository_url' => $data['repository_url'] ?? null,
                'pr_url' => $data['pr_url'] ?? null,
                'bounty_credits' => $bounty,
                'status' => 'open',
            ]);
        });
    }

    /**
     * Submit a code review feedback for a request.
     */
    public function submitReview(User $reviewer, CodeReviewRequest $request, string $feedback): CodeReviewSubmission
    {
        if ($request->status === 'completed' || $request->status === 'cancelled') {
            throw new InvalidArgumentException('This request is no longer accepting reviews.');
        }

        if ($request->user_id === $reviewer->id) {
            throw new InvalidArgumentException('You cannot review your own request.');
        }

        $submission = CodeReviewSubmission::create([
            'code_review_request_id' => $request->id,
            'reviewer_id' => $reviewer->id,
            'feedback' => $feedback,
            'status' => 'pending',
        ]);

        if ($request->status === 'open') {
            $request->update(['status' => 'in_review']);
        }

        return $submission;
    }

    /**
     * Accept a review submission and transfer/award bounty credits to reviewer.
     */
    public function acceptReview(CodeReviewSubmission $submission): void
    {
        DB::transaction(function () use ($submission) {
            $submission = CodeReviewSubmission::where('id', $submission->id)->lockForUpdate()->firstOrFail();
            $request = CodeReviewRequest::where('id', $submission->code_review_request_id)->lockForUpdate()->firstOrFail();

            if ($request->status === 'completed') {
                throw new InvalidArgumentException('Request is already completed.');
            }

            if ($submission->status === 'accepted') {
                throw new InvalidArgumentException('Submission is already accepted.');
            }

            $submission->update(['status' => 'accepted']);
            $request->update(['status' => 'completed']);

            if ($request->bounty_credits > 0) {
                $this->creditLedgerService->awardCredits(
                    $submission->reviewer,
                    $request->bounty_credits,
                    "Bounty earned for code review: {$request->title}",
                    $request
                );
            }
        });
    }
}
