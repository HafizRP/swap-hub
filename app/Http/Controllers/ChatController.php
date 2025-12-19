<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMessageNotification;

class ChatController extends Controller
{
    /**
     * Display a listing of conversations.
     */
    public function index()
    {
        $conversations = auth()->user()->conversations()
            ->with(['latestMessage', 'participants'])
            ->latest()
            ->paginate(20);

        return view('chat.index', compact('conversations'));
    }

    /**
     * Display a specific conversation.
     */
    public function show(\App\Models\Conversation $conversation)
    {
        // Authorize participant
        $isParticipant = $conversation->participants()->where('user_id', auth()->id())->exists();

        if (!$isParticipant) {
            // Check if it's a project conversation and user is a member
            if ($conversation->type === 'project' && $conversation->project && $conversation->project->members()->where('user_id', auth()->id())->exists()) {
                // Auto-sync the user to the conversation
                $conversation->participants()->syncWithoutDetaching([auth()->id()]);
            } else {
                abort(403, 'You are not a participant in this conversation.');
            }
        }

        $messages = $conversation->messages()->with('user')->get();

        // Mark messages as read
        $conversation->participants()->updateExistingPivot(auth()->id(), [
            'last_read_at' => now()
        ]);

        return view('chat.show', compact('conversation', 'messages'));
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, \App\Models\Conversation $conversation)
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        // Broadcast the event
        broadcast(new MessageSent($message->load('user')));

        // Optional: Send email notifications to other participants
        // Uncomment the code below if you want email notifications for new messages
        // Note: Consider using queues to avoid performance issues
        /*
        $participants = $conversation->participants()
            ->where('user_id', '!=', auth()->id())
            ->get();

        foreach ($participants as $participant) {
            Mail::to($participant->email)->send(
                new NewMessageNotification($message, auth()->user(), $conversation, $participant)
            );
        }
        */

        if ($request->wantsJson()) {
            return response()->json($message->load('user'));
        }

        return back()->with('status', 'message-sent');
    }

    /**
     * Get messages for polling.
     */
    public function getMessages(\App\Models\Conversation $conversation)
    {
        if (!$conversation->participants()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'user_avatar' => $message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=6366f1&color=fff',
                    'created_at' => $message->created_at->format('H:i'),
                ];
            });

        return response()->json($messages);
    }

    /**
     * Create or retrieve a direct conversation with another user.
     */
    public function createDirectConversation(\App\Models\User $user)
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

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'type' => 'direct',
                'name' => null, // Direct chats use participant names
            ]);

            $conversation->participants()->attach([auth()->id(), $user->id]);
        }

        return redirect()->route('chat.show', $conversation);
    }
}
