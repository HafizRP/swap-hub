<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GitHubActivity extends Model
{
    use HasFactory;

    protected $table = 'github_activities';

    protected $guarded = ['id'];

    protected $casts = [
        'metadata' => 'array',
        'activity_at' => 'datetime',
    ];

    protected $appends = [
        'type',
        'payload',
    ];

    public function getTypeAttribute(): string
    {
        return $this->activity_type ?? 'commit';
    }

    public function getPayloadAttribute(): array
    {
        if (is_array($this->metadata)) {
            return $this->metadata;
        }

        return is_string($this->metadata) ? (json_decode($this->metadata, true) ?: []) : [];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
