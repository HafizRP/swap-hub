<?php

namespace Tests\Feature;

use App\Http\Controllers\ResumeController;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ResumeSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_ssrf_urls_are_blocked_by_avatar_validator(): void
    {
        $controller = new ResumeController;

        $this->assertFalse($controller->isValidAvatarUrl('http://169.254.169.254/latest/meta-data/'));
        $this->assertFalse($controller->isValidAvatarUrl('http://127.0.0.1/admin'));
        $this->assertFalse($controller->isValidAvatarUrl('http://localhost:8000/info'));
        $this->assertFalse($controller->isValidAvatarUrl('http://10.0.0.1/secret'));
        $this->assertFalse($controller->isValidAvatarUrl('http://192.168.1.1/router'));
        $this->assertFalse($controller->isValidAvatarUrl('http://172.16.0.1/internal'));
        $this->assertFalse($controller->isValidAvatarUrl('http://metadata.google.internal/computeMetadata/v1/'));
        $this->assertFalse($controller->isValidAvatarUrl('file:///etc/passwd'));
        $this->assertFalse($controller->isValidAvatarUrl('ftp://example.com/avatar.jpg'));
    }

    public function test_resume_download_does_not_fetch_ssrf_avatar_url(): void
    {
        Http::fake([
            'https://ui-avatars.com/*' => Http::response('fake-avatar-bytes', 200),
        ]);

        $user = User::factory()->create([
            'avatar' => 'http://169.254.169.254/latest/meta-data/iam/security-credentials/',
        ]);

        $response = $this->actingAs($user)->get(route('profile.resume', $user));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        Http::assertNotSent(function ($request) {
            return str_contains($request->url(), '169.254.169.254');
        });
    }
}
