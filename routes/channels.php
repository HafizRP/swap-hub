<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // Allow if user is a direct participant
    if ($conversation->participants()->where('user_id', $user->id)->exists()) {
        return true;
    }

    // Allow if user is a member of the project involved in the chat
    if ($conversation->type === 'project' && $conversation->project) {
        return $conversation->project->members()->where('user_id', $user->id)->exists()
            || $conversation->project->owner_id === $user->id;
    }

    return false;
});
