<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'is_validated',
        'contribution_rating',
        'contribution_notes',
        'joined_at',
    ];

    protected $casts = [
        'is_validated' => 'boolean',
        'joined_at' => 'datetime',
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
