<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CreditLedgerService
{
    /**
     * Award credits to a user and update balance atomically.
     */
    public function awardCredits(User $user, int $amount, string $reason, ?Model $reference = null): CreditTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive.');
        }

        return DB::transaction(function () use ($user, $amount, $reason, $reference) {
            $user->increment('credits', $amount);

            return CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'award',
                'reason' => $reason,
                'reference_type' => $reference ? $reference->getMorphClass() : null,
                'reference_id' => $reference ? $reference->getKey() : null,
            ]);
        });
    }

    /**
     * Spend credits from a user after validating balance.
     */
    public function spendCredits(User $user, int $amount, string $reason, ?Model $reference = null): CreditTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive.');
        }

        return DB::transaction(function () use ($user, $amount, $reason, $reference) {
            $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();
            if ($user->credits < $amount) {
                throw new InvalidArgumentException('Insufficient credit balance.');
            }

            $user->decrement('credits', $amount);

            return CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => -$amount,
                'type' => 'spend',
                'reason' => $reason,
                'reference_type' => $reference ? $reference->getMorphClass() : null,
                'reference_id' => $reference ? $reference->getKey() : null,
            ]);
        });
    }

    /**
     * Transfer credits atomically from one user to another.
     */
    public function transferCredits(User $from, User $to, int $amount, string $reason): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive.');
        }

        DB::transaction(function () use ($from, $to, $amount, $reason) {
            $from = User::where('id', $from->id)->lockForUpdate()->firstOrFail();
            $to = User::where('id', $to->id)->lockForUpdate()->firstOrFail();
            if ($from->credits < $amount) {
                throw new InvalidArgumentException('Insufficient credit balance for transfer.');
            }

            $from->decrement('credits', $amount);
            $to->increment('credits', $amount);

            CreditTransaction::create([
                'user_id' => $from->id,
                'amount' => -$amount,
                'type' => 'transfer_out',
                'reason' => $reason,
                'reference_type' => User::class,
                'reference_id' => $to->id,
            ]);

            CreditTransaction::create([
                'user_id' => $to->id,
                'amount' => $amount,
                'type' => 'transfer_in',
                'reason' => $reason,
                'reference_type' => User::class,
                'reference_id' => $from->id,
            ]);
        });
    }
}
