<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class ChatPage extends Component
{
    use WithFileUploads;

    public $conversationId = null;
    public $conversation = null;
    public $messages = [];
    public $newMessage = '';
    public $loading = false;
    public $attachments = [];
    public $activeTab = 'chat'; // chat, tasks, files

    public function mount($conversation = null)
    {
        if ($conversation) {
            $this->selectConversation($conversation);
        }
    }

    public function selectConversation($conversationId)
    {
        $this->conversationId = $conversationId;

        // Load conversation
        $this->conversation = Conversation::with(['participants', 'project'])
            ->findOrFail($conversationId);

        // Reset tab to chat when switching
        $this->activeTab = 'chat';

        // Update sidebar
        $this->dispatch('update-conversation-ui', conversationId: $conversationId);

        // Check authorization
        if (!$this->conversation->participants->contains(auth()->id())) {
            abort(403);
        }

        // Load messages
        $this->loadMessages();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    #[On('conversation-switched')]
    public function handleConversationSwitch($conversationId)
    {
        $this->selectConversation($conversationId);
    }

    public function loadMessages()
    {
        if (!$this->conversation) {
            $this->messages = [];
            return;
        }

        // Mark as read
        $this->conversation->participants()->updateExistingPivot(auth()->id(), [
            'last_read_at' => now(),
        ]);
        // Notify sidebar to refresh badges if needed (optional, but good for sync)
        $this->dispatch('refresh-conversation-list');

        $this->messages = $this->conversation->messages()
            ->with(['user', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user ? $message->user->name : 'System',
                    'user_avatar' => $message->user
                        ? ($message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=6366f1&color=fff')
                        : 'https://ui-avatars.com/api/?name=System&background=10b981&color=fff',
                    'created_at_human' => $message->created_at->format('H:i'),
                    'attachments' => $message->attachments->map(function ($att) {
                        return [
                            'id' => $att->id,
                            'file_path' => asset('storage/' . $att->file_path),
                            'file_name' => $att->file_name,
                            'file_type' => $att->file_type, // MIME type
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function sendMessage()
    {
        if (!$this->conversation || (empty(trim($this->newMessage)) && empty($this->attachments))) {
            return;
        }

        $this->validate([
            'newMessage' => 'nullable|string|max:5000',
            'attachments.*' => 'file|max:10240', // Validate each file
            'attachments' => 'max:5', // Max 5 files
        ]);

        $this->loading = true;

        $message = $this->conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $this->newMessage ?? '',
        ]);

        $this->conversation->touch();

        $savedAttachments = [];
        foreach ($this->attachments as $file) {
            $path = $file->store('chat-attachments', 'public');
            $att = $message->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
            ]);

            $savedAttachments[] = [
                'id' => $att->id,
                'file_path' => asset('storage/' . $path),
                'file_name' => $att->file_name,
                'file_type' => $att->file_type,
            ];
        }

        // Broadcast event
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        // Add to local messages
        $this->messages[] = [
            'id' => $message->id,
            'content' => $message->content,
            'user_id' => $message->user_id,
            'user_name' => auth()->user()->name,
            'user_avatar' => auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff',
            'created_at' => $message->created_at->toISOString(),
            'created_at_human' => $message->created_at->format('H:i'),
            'attachments' => $savedAttachments,
        ];

        $this->newMessage = '';
        $this->attachments = [];
        $this->loading = false;

        $this->dispatch('message-sent');
        $this->dispatch('scroll-to-bottom');
    }

    public function getTitle()
    {
        if ($this->conversation) {
            $otherParticipant = $this->conversation->participants
                ->where('id', '!=', auth()->id())
                ->first();

            return $this->conversation->name ?? ($otherParticipant->name ?? 'Chat');
        }

        return 'Chat';
    }

    public function render()
    {
        return view('livewire.chat.chat-page');
    }
}
