<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'major',
        'university',
        'bio',
        'avatar',
        'github_username',
        'github_token',
        'reputation_points',
        'phone',
        'graduation_year',
    ];

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
    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withPivot('proficiency_level')->withTimestamps();
    }

    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role', 'is_validated', 'contribution_rating', 'contribution_notes', 'joined_at')
            ->withTimestamps();
    }

    public function skillSwapRequestsSent()
    {
        return $this->hasMany(SkillSwapRequest::class, 'requester_id');
    }

    public function skillSwapRequestsReceived()
    {
        return $this->hasMany(SkillSwapRequest::class, 'provider_id');
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->withPivot('last_read_at')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function githubActivities()
    {
        return $this->hasMany(GitHubActivity::class);
    }
}
