<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class OAuthRoleAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_github_auth_assigns_default_student_role_on_registration(): void
    {
        $githubUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $githubUser->token = 'mock-github-token-xyz';
        $githubUser->shouldReceive('getId')->andReturn('github-999');
        $githubUser->shouldReceive('getNickname')->andReturn('octostudent');
        $githubUser->shouldReceive('getName')->andReturn('Octo Student');
        $githubUser->shouldReceive('getEmail')->andReturn('octostudent@example.com');

        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andReturn($githubUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

        $response = $this->get('/auth/github/callback');

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'octostudent@example.com')->first();
        $this->assertNotNull($user);

        $studentRole = Role::where('slug', 'student')->first();
        $this->assertEquals($studentRole->id, $user->role_id);
    }

    public function test_google_auth_assigns_default_student_role_on_registration(): void
    {
        $googleUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $googleUser->shouldReceive('getId')->andReturn('google-888');
        $googleUser->shouldReceive('getName')->andReturn('Google Student');
        $googleUser->shouldReceive('getEmail')->andReturn('googlestudent@example.com');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar.jpg');

        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'googlestudent@example.com')->first();
        $this->assertNotNull($user);

        $studentRole = Role::where('slug', 'student')->first();
        $this->assertEquals($studentRole->id, $user->role_id);
    }
}
