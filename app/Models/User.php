<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot('proficiency_level')->withTimestamps();
    }

    public function ownedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role', 'is_validated', 'contribution_rating', 'contribution_notes', 'joined_at')
            ->withTimestamps();
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class)->withPivot('last_read_at')->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function githubActivities(): HasMany
    {
        return $this->hasMany(GitHubActivity::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function sentSkillSwaps(): HasMany
    {
        return $this->hasMany(SkillSwapRequest::class, 'requester_id');
    }

    public function receivedSkillSwaps(): HasMany
    {
        return $this->hasMany(SkillSwapRequest::class, 'provider_id');
    }

    // Helper Methods
    public function hasRole($roleSlug): bool
    {
        return $this->role && $this->role->slug === $roleSlug;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }
}
