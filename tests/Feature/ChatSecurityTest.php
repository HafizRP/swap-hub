<?php

namespace Tests\Feature;

use App\Livewire\Chat\ChatPage;
use App\Models\Conversation;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ChatSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_chat_file_upload_rejects_executable_scripts(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $conversation = Conversation::create([
            'type' => 'direct',
            'name' => 'Direct Chat',
        ]);
        $conversation->participants()->attach($user->id);

        $dangerousFile = UploadedFile::fake()->create('exploit.php', 10, 'text/x-php');

        Livewire::actingAs($user)
            ->test(ChatPage::class, ['conversation' => $conversation->id])
            ->set('attachments', [$dangerousFile])
            ->call('sendMessage')
            ->assertHasErrors(['attachments.0']);
    }

    public function test_chat_file_upload_accepts_valid_documents_and_images(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $conversation = Conversation::create([
            'type' => 'direct',
            'name' => 'Direct Chat',
        ]);
        $conversation->participants()->attach($user->id);

        $validFile = UploadedFile::fake()->create('document.pdf', 50, 'application/pdf');

        Livewire::actingAs($user)
            ->test(ChatPage::class, ['conversation' => $conversation->id])
            ->set('newMessage', 'Here is the project proposal')
            ->set('attachments', [$validFile])
            ->call('sendMessage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'content' => 'Here is the project proposal',
        ]);
    }

    public function test_chat_markdown_sanitizes_xss_scripts_and_javascript_links(): void
    {
        $maliciousPayload = 'Hello <script>alert("XSS")</script><img src=x onerror=alert(1)> [Click Me](javascript:alert(1)) **Safe text**';

        $rendered = Str::markdown($maliciousPayload, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $this->assertStringNotContainsString('<script>', $rendered);
        $this->assertStringNotContainsString('onerror', $rendered);
        $this->assertStringNotContainsString('href="javascript:', $rendered);
        $this->assertStringContainsString('<strong>Safe text</strong>', $rendered);
    }

    public function test_chat_file_upload_rejects_svg_files(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $conversation = Conversation::create([
            'type' => 'direct',
            'name' => 'Direct Chat',
        ]);
        $conversation->participants()->attach($user->id);

        $svgFile = UploadedFile::fake()->create('exploit.svg', 10, 'image/svg+xml');

        Livewire::actingAs($user)
            ->test(ChatPage::class, ['conversation' => $conversation->id])
            ->set('attachments', [$svgFile])
            ->call('sendMessage')
            ->assertHasErrors(['attachments.0']);
    }
}
