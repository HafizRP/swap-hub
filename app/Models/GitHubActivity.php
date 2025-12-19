<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class GitHubActivity extends Model
{
    use HasFactory;

    protected $table = 'github_activities';
    protected $fillable = [
        'project_id',
        'user_id',
        'activity_type',
        'commit_sha',
        'commit_message',
        'branch',
        'additions',
        'deletions',
        'metadata',
        'activity_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'activity_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
