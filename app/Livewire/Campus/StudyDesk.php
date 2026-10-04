<?php

declare(strict_types=1);

namespace App\Livewire\Campus;

use App\Models\StudySession;
use App\Models\User;
use App\Services\StudyDeskService;
use Carbon\Carbon;
use Livewire\Component;

class StudyDesk extends Component
{
    public string $title = '';

    public string $description = '';

    public string $startsAt = '';

    public int $durationMinutes = 60;

    public function createSession(StudyDeskService $studyDeskService): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'startsAt' => 'required|date|after:now',
            'durationMinutes' => 'required|integer|min:15|max:360',
        ]);

        /** @var User $host */
        $host = User::findOrFail(auth()->id());

        $startsAt = Carbon::parse($this->startsAt);
        $endsAt = $startsAt->copy()->addMinutes($this->durationMinutes);

        $session = $studyDeskService->createSession($host, [
            'title' => trim($this->title),
            'description' => $this->description ? trim($this->description) : null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'participants' => [$host->id],
        ]);

        $this->reset(['title', 'description', 'startsAt', 'durationMinutes']);
        session()->flash('message', 'Sesi belajar berhasil dibuat!');
    }

    public function joinSession(int $sessionId): void
    {
        $session = StudySession::findOrFail($sessionId);

        $session->participants()->syncWithoutDetaching([auth()->id()]);
        session()->flash('message', 'Berhasil bergabung dengan sesi belajar!');
    }

    public function leaveSession(int $sessionId): void
    {
        $session = StudySession::findOrFail($sessionId);

        if ($session->host_id === auth()->id()) {
            $this->addError('session', 'Host tidak dapat keluar dari sesi belajar.');

            return;
        }

        $session->participants()->detach(auth()->id());
        session()->flash('message', 'Anda telah meninggalkan sesi belajar.');
    }

    public function completeSession(int $sessionId, StudyDeskService $studyDeskService): void
    {
        $session = StudySession::with(['host', 'participants'])->findOrFail($sessionId);

        if ($session->host_id !== auth()->id()) {
            abort(403, 'Hanya host yang dapat menandai sesi selesai.');
        }

        $studyDeskService->completeSession($session);
        session()->flash('message', 'Sesi belajar selesai dan kredit berhasil diberikan!');
    }

    public function render()
    {
        $activeSessions = StudySession::with(['host', 'participants'])
            ->whereIn('status', ['scheduled', 'active'])
            ->orderBy('starts_at', 'asc')
            ->take(20)
            ->get();

        $mySessions = StudySession::with(['host', 'participants'])
            ->where(function ($q) {
                $q->where('host_id', auth()->id())
                    ->orWhereHas('participants', function ($p) {
                        $p->where('users.id', auth()->id());
                    });
            })
            ->latest()
            ->take(20)
            ->get();

        return view('livewire.campus.study-desk', [
            'activeSessions' => $activeSessions,
            'mySessions' => $mySessions,
        ])->layout('layouts.app');
    }
}
