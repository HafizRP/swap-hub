<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\GitHubWebhookService;
use Tests\TestCase;

class GitHubWebhookServiceTest extends TestCase
{
    private GitHubWebhookService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GitHubWebhookService;
    }

    public function test_verify_signature_returns_true_for_valid_hmac(): void
    {
        $payload = json_encode(['ref' => 'refs/heads/main']);
        $secret = 'test_webhook_secret_key';
        $signature = 'sha256='.hash_hmac('sha256', $payload, $secret);

        $result = $this->service->verifySignature($payload, $signature, $secret);

        $this->assertTrue($result);
    }

    public function test_verify_signature_returns_false_for_invalid_hmac(): void
    {
        $payload = json_encode(['ref' => 'refs/heads/main']);
        $secret = 'test_webhook_secret_key';
        $signature = 'sha256=invalid_hash_string_here';

        $result = $this->service->verifySignature($payload, $signature, $secret);

        $this->assertFalse($result);
    }

    public function test_verify_signature_returns_false_for_missing_sha256_prefix(): void
    {
        $payload = json_encode(['ref' => 'refs/heads/main']);
        $secret = 'test_webhook_secret_key';
        $signature = hash_hmac('sha256', $payload, $secret);

        $result = $this->service->verifySignature($payload, $signature, $secret);

        $this->assertFalse($result);
    }

    public function test_verify_signature_returns_false_for_null_or_empty_values(): void
    {
        $this->assertFalse($this->service->verifySignature('payload', null, 'secret'));
        $this->assertFalse($this->service->verifySignature('payload', 'sha256=hash', ''));
    }
}
