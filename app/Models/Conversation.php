<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('last_read_at')->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function formatForChatList($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $otherParticipant = $this->participants->where('id', '!=', $userId)->first();

        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name ?? ($otherParticipant->name ?? 'Unknown'),
            'avatar' => $this->type === 'project'
                ? 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=4f46e5&color=fff'
                : ($otherParticipant->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($otherParticipant->name ?? 'U').'&background=10b981&color=fff'),
            'latest_message' => $this->latestMessage->content ?? 'No messages yet...',
            'latest_message_time' => $this->latestMessage ? $this->latestMessage->created_at->format('H:i') : '',
            'unread' => $this->pivot->last_read_at < ($this->latestMessage->created_at ?? now()->subYear()),
        ];
    }
}
