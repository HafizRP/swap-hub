<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Create or retrieve a direct conversation with another user.
     */
    public function createDirectConversation(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot chat with yourself.');
        }

        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Check if direct conversation already exists
        $conversation = $currentUser->conversations()
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

            $conversation->participants()->attach([Auth::id(), $user->id]);
        }

        return redirect()->route('chat', $conversation);
    }

    /**
     * Store a message in a conversation.
     */
    public function storeMessage(StoreMessageRequest $request): RedirectResponse
    {
        $conversation = Conversation::with('participants')->findOrFail($request->conversation_id);

        if (! $conversation->participants->contains(Auth::id())) {
            abort(403, 'Anda bukan peserta percakapan ini.');
        }

        $message = \App\Models\Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'content' => trim($request->message),
        ]);

        event(new \App\Events\MessageSent($message));

        return back()->with('status', 'message-sent');
    }
}
