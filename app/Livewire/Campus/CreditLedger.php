<?php

declare(strict_types=1);

namespace App\Livewire\Campus;

use App\Models\CreditTransaction;
use App\Models\User;
use App\Services\CreditLedgerService;
use Livewire\Component;

class CreditLedger extends Component
{
    public string $recipientEmail = '';

    public int $transferAmount = 1;

    public string $transferDescription = '';

    public function transferCredits(CreditLedgerService $creditLedgerService): void
    {
        /** @var User $sender */
        $sender = User::findOrFail(auth()->id());

        $this->validate([
            'recipientEmail' => 'required|email|exists:users,email',
            'transferAmount' => 'required|integer|min:1|max:'.$sender->credits,
            'transferDescription' => 'nullable|string|max:255',
        ], [
            'transferAmount.max' => 'Saldo kredit Anda tidak mencukupi untuk mentransfer jumlah ini.',
            'recipientEmail.exists' => 'Pengguna dengan email ini tidak ditemukan.',
        ]);

        if ($sender->email === $this->recipientEmail) {
            $this->addError('recipientEmail', 'Anda tidak dapat mentransfer kredit ke diri sendiri.');

            return;
        }

        $recipient = User::where('email', $this->recipientEmail)->firstOrFail();

        try {
            $reason = 'Transfer'.($this->transferDescription ? ': '.$this->transferDescription : '');
            $creditLedgerService->transferCredits($sender, $recipient, $this->transferAmount, $reason);
        } catch (\Throwable $e) {
            $this->addError('transferAmount', $e->getMessage());

            return;
        }

        $this->reset(['recipientEmail', 'transferAmount', 'transferDescription']);
        session()->flash('message', 'Kredit berhasil ditransfer!');
    }

    public function render()
    {
        $user = User::findOrFail(auth()->id());

        $transactions = CreditTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('livewire.campus.credit-ledger', [
            'transactions' => $transactions,
            'user' => $user,
        ])->layout('layouts.app');
    }
}
