<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdjustCreditRequest;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Services\AdminAuditService;
use App\Services\CreditLedgerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreditController extends Controller
{
    public function index(Request $request): View
    {
        $query = CreditTransaction::with('user');

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('reason', 'like', "%{$search}%");
        }

        $transactions = $query->latest()->paginate(25)->withQueryString();
        $users = User::orderBy('name')->select('id', 'name', 'email', 'credits')->get();

        return view('admin.credits.index', compact('transactions', 'users'));
    }

    public function adjust(
        AdjustCreditRequest $request,
        CreditLedgerService $creditLedgerService,
        AdminAuditService $auditService
    ): RedirectResponse {
        $validated = $request->validated();
        $targetUser = User::findOrFail($validated['user_id']);
        $amount = (int) $validated['amount'];
        $reason = 'Admin adjustment: '.$validated['reason'];

        /** @var User $admin */
        $admin = Auth::user();

        if ($amount > 0) {
            $creditLedgerService->awardCredits($targetUser, $amount, $reason, $admin);
        } else {
            $spendAmount = abs($amount);
            if ($targetUser->credits < $spendAmount) {
                return back()->with('error', "Saldo pengguna ({$targetUser->credits}) tidak mencukupi untuk dikurangi {$spendAmount}.");
            }
            $creditLedgerService->spendCredits($targetUser, $spendAmount, $reason, $admin);
        }

        $auditService->log($admin, 'credit.adjust', $targetUser, [
            'amount' => $amount,
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', "Saldo kredit {$targetUser->name} berhasil disesuaikan ({$amount} kredit).");
    }
}
