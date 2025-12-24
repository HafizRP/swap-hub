<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.' . $this->message->conversation_id),
        ];

        foreach ($this->message->conversation->participants as $participant) {
            $channels[] = new PrivateChannel('App.Models.User.' . $participant->id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'content' => $this->stripMarkdown($this->message->content),
            'user_id' => $this->message->user_id,
            'user_name' => $this->message->user ? $this->message->user->name : 'System',
            'user_avatar' => $this->message->user
                ? ($this->message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->message->user->name) . '&background=6366f1&color=fff')
                : 'https://ui-avatars.com/api/?name=System&background=10b981&color=fff',
            'conversation_name' => $this->message->conversation->name,
            'conversation_type' => $this->message->conversation->type,
            'created_at' => $this->message->created_at->toISOString(),
            'attachments' => $this->message->attachments->map(function ($att) {
                return [
                    'id' => $att->id,
                    'file_path' => asset('storage/' . $att->file_path),
                    'file_name' => $att->file_name,
                    'file_type' => $att->file_type,
                ];
            })->toArray(),
        ];
    }

    /**
     * Strip markdown formatting for notifications
     */
    private function stripMarkdown(string $content): string
    {
        // Remove bold (**text**)
        $content = preg_replace('/\*\*(.*?)\*\*/', '$1', $content);
        // Remove code (`text`)
        $content = preg_replace('/`(.*?)`/', '$1', $content);
        return $content;
    }
}
