<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkillSwapRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'provider_id',
        'offered_skill_id',
        'requested_skill_id',
        'description',
        'points_offered',
        'status',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function offeredSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'offered_skill_id');
    }

    public function requestedSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'requested_skill_id');
    }
}
