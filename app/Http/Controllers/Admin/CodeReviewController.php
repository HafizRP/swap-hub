<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodeReviewRequest;
use App\Models\User;
use App\Services\AdminAuditService;
use App\Services\CreditLedgerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CodeReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = CodeReviewRequest::with(['user', 'submissions.reviewer']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('admin.code-reviews.index', compact('requests'));
    }

    public function cancel(
        CodeReviewRequest $codeReview,
        CreditLedgerService $creditLedgerService,
        AdminAuditService $auditService
    ): RedirectResponse {
        if ($codeReview->status === 'completed' || $codeReview->status === 'cancelled') {
            return back()->with('error', 'Permintaan code review ini sudah selesai atau telah dibatalkan.');
        }

        /** @var User $admin */
        $admin = Auth::user();

        // Refund bounty if was deducted
        if ($codeReview->bounty_credits > 0) {
            $creditLedgerService->awardCredits(
                $codeReview->user,
                $codeReview->bounty_credits,
                "Pengembalian bounty pembatalan admin untuk: {$codeReview->title}",
                $admin
            );
        }

        $codeReview->update(['status' => 'cancelled']);

        $auditService->log($admin, 'code_review.cancel', $codeReview, [
            'bounty_refunded' => $codeReview->bounty_credits,
            'owner_id' => $codeReview->user_id,
        ]);

        return back()->with('success', 'Permintaan code review berhasil dibatalkan dan bounty dikembalikan ke pemohon.');
    }
}
