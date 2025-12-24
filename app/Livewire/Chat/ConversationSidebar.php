<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\Attributes\On;

class ConversationSidebar extends Component
{
    public $conversations = [];
    public $searchQuery = '';
    public $currentConversationId;

    #[On('update-conversation-ui')]
    public function updateActiveConversation($conversationId)
    {
        $this->currentConversationId = $conversationId;
        $this->loadConversations();
    }

    public function mount($currentConversationId = null)
    {
        $this->currentConversationId = $currentConversationId;
        $this->loadConversations();
    }

    #[On('refresh-conversation-list')]
    public function refreshList()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $userId = auth()->id();
        $this->conversations = auth()->user()
            ->conversations()
            ->with(['latestMessage.attachments', 'participants'])
            ->withCount([
                'messages as unread_count' => function ($query) use ($userId) {
                    $query->where('user_id', '!=', $userId)
                        ->whereRaw('created_at > (SELECT COALESCE(last_read_at, "1970-01-01") FROM conversation_user WHERE conversation_id = messages.conversation_id AND user_id = ?)', [$userId]);
                }
            ])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($conv) {
                $otherParticipant = $conv->participants->where('id', '!=', auth()->id())->first();
                return [
                    'id' => $conv->id,
                    'type' => $conv->type,
                    'name' => $conv->name ?? ($otherParticipant->name ?? 'Unknown'),
                    'avatar' => $conv->type === 'project'
                        ? 'https://ui-avatars.com/api/?name=' . urlencode($conv->name) . '&background=4f46e5&color=fff'
                        : ($otherParticipant->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherParticipant->name ?? 'U') . '&background=10b981&color=fff'),
                    'latest_message' => ($conv->latestMessage->content ?? null) ?: (($conv->latestMessage?->attachments->isNotEmpty()) ? '[Attachment]' : 'Start a conversation...'),
                    'is_active' => $conv->id == $this->currentConversationId,
                    'unread_count' => $conv->unread_count,
                ];
            });
    }

    public function switchConversation($conversationId)
    {
        $this->currentConversationId = $conversationId;
        $this->loadConversations();
        $this->dispatch('conversation-switched', conversationId: $conversationId);
    }

    public function updatedSearchQuery()
    {
        $this->loadConversations();
    }

    public function getFilteredConversationsProperty()
    {
        if (!$this->searchQuery) {
            return $this->conversations;
        }

        return collect($this->conversations)->filter(function ($conv) {
            return stripos($conv['name'], $this->searchQuery) !== false;
        })->values()->all();
    }

    public function render()
    {
        return view('livewire.chat.conversation-sidebar');
    }
}
