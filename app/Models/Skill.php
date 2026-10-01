<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('proficiency_level')->withTimestamps();
    }

    public function swapRequestsOffered(): HasMany
    {
        return $this->hasMany(SkillSwapRequest::class, 'offered_skill_id');
    }

    public function swapRequestsRequested(): HasMany
    {
        return $this->hasMany(SkillSwapRequest::class, 'requested_skill_id');
    }
}
