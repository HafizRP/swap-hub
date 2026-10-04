<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkillSwapRequest;
use App\Models\User;
use App\Services\AdminAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillSwapController extends Controller
{
    public function index(Request $request): View
    {
        $query = SkillSwapRequest::with(['requester', 'provider', 'offeredSkill', 'requestedSkill']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('requester', function ($rq) use ($search) {
                    $rq->where('name', 'like', "%{$search}%");
                })->orWhereHas('provider', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                });
            });
        }

        $swaps = $query->latest()->paginate(20)->withQueryString();

        return view('admin.swaps.index', compact('swaps'));
    }

    public function cancel(SkillSwapRequest $skillSwap, AdminAuditService $auditService): RedirectResponse
    {
        if ($skillSwap->status === 'completed' || $skillSwap->status === 'cancelled') {
            return back()->with('error', 'Sesi skill swap ini sudah selesai atau telah dibatalkan.');
        }

        /** @var User $admin */
        $admin = Auth::user();

        $skillSwap->update(['status' => 'cancelled']);

        $auditService->log($admin, 'swap.admin_cancel', $skillSwap, [
            'requester_id' => $skillSwap->requester_id,
            'provider_id' => $skillSwap->provider_id,
        ]);

        return back()->with('success', 'Skill swap berhasil dibatalkan oleh admin.');
    }

    public function complete(SkillSwapRequest $skillSwap, AdminAuditService $auditService): RedirectResponse
    {
        if ($skillSwap->status === 'completed') {
            return back()->with('error', 'Sesi skill swap ini sudah selesai.');
        }

        /** @var User $admin */
        $admin = Auth::user();

        $skillSwap->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $auditService->log($admin, 'swap.admin_complete', $skillSwap, [
            'requester_id' => $skillSwap->requester_id,
            'provider_id' => $skillSwap->provider_id,
        ]);

        return back()->with('success', 'Skill swap berhasil diselesaikan oleh admin.');
    }
}
