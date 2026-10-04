<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\StudySession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudyDeskService
{
    public function __construct(
        protected CreditLedgerService $creditLedgerService
    ) {}

    /**
     * Create a new study session with generated Jitsi meeting URL.
     */
    public function createSession(User $host, array $data): StudySession
    {
        $slug = Str::slug($data['title'] ?? 'study-session');
        $random = Str::random(8);
        $meetingUrl = "https://meet.jit.si/swaphub-{$slug}-{$random}";

        $session = StudySession::create([
            'host_id' => $host->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'meeting_url' => $meetingUrl,
            'status' => 'scheduled',
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        if (isset($data['participants']) && is_array($data['participants'])) {
            $session->participants()->sync($data['participants']);
        }

        return $session;
    }

    /**
     * Complete study session and optionally award credits to host & participants.
     */
    public function completeSession(StudySession $session, int $creditAward = 10): void
    {
        DB::transaction(function () use ($session, $creditAward) {
            $session->update(['status' => 'completed']);

            if ($creditAward > 0) {
                $this->creditLedgerService->awardCredits(
                    $session->host,
                    $creditAward,
                    "Hosted study session: {$session->title}",
                    $session
                );

                foreach ($session->participants as $participant) {
                    $this->creditLedgerService->awardCredits(
                        $participant,
                        $creditAward,
                        "Attended study session: {$session->title}",
                        $session
                    );
                }
            }
        });
    }
}
