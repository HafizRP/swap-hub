<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;

class ChatList extends Component
{
    public $conversations = [];
    public $searchQuery = '';
    public $filterType = 'all';
    public $selectedConversationId = null;

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $query = auth()->user()
            ->conversations()
            ->with(['latestMessage', 'participants']);

        $this->conversations = $query->get()->map(function ($conv) {
            $otherParticipant = $conv->participants->where('id', '!=', auth()->id())->first();
            return [
                'id' => $conv->id,
                'type' => $conv->type,
                'name' => $conv->name ?? ($otherParticipant->name ?? 'Unknown'),
                'avatar' => $conv->type === 'project'
                    ? 'https://ui-avatars.com/api/?name=' . urlencode($conv->name) . '&background=4f46e5&color=fff'
                    : ($otherParticipant->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherParticipant->name ?? 'U') . '&background=10b981&color=fff'),
                'latest_message' => $conv->latestMessage->content ?? 'No messages yet...',
                'latest_message_time' => $conv->latestMessage ? $conv->latestMessage->created_at->format('H:i') : '',
                'unread' => $conv->pivot->last_read_at < ($conv->latestMessage->created_at ?? now()->subYear()),
            ];
        });
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;

        // Redirect to chat page with conversation
        return $this->redirect(route('chat', $conversationId), navigate: true);
    }

    public function updatedSearchQuery()
    {
        // Filter will be handled by computed property
    }

    public function updatedFilterType()
    {
        // Filter will be handled by computed property
    }

    public function getFilteredConversationsProperty()
    {
        $filtered = collect($this->conversations);

        // Apply type filter
        if ($this->filterType !== 'all') {
            $filtered = $filtered->filter(function ($conv) {
                return $conv['type'] === $this->filterType;
            });
        }

        // Apply search filter
        if ($this->searchQuery) {
            $filtered = $filtered->filter(function ($conv) {
                return stripos($conv['name'], $this->searchQuery) !== false;
            });
        }

        return $filtered->values()->all();
    }

    public function render()
    {
        return view('livewire.chat.chat-list');
    }
}
