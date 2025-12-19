<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('proficiency_level')->withTimestamps();
    }

    public function swapRequestsOffered()
    {
        return $this->hasMany(SkillSwapRequest::class, 'offered_skill_id');
    }

    public function swapRequestsRequested()
    {
        return $this->hasMany(SkillSwapRequest::class, 'requested_skill_id');
    }
}
