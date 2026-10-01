<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ChatController extends Controller
{
    /**
     * Create or retrieve a direct conversation with another user.
     */
    public function createDirectConversation(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot chat with yourself.');
        }

        // Check if direct conversation already exists
        $conversation = auth()->user()->conversations()
            ->where('type', 'direct')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'type' => 'direct',
                'name' => null, // Direct chats use participant names
            ]);

            $conversation->participants()->attach([auth()->id(), $user->id]);
        }

        return redirect()->route('chat', $conversation);
    }
}
