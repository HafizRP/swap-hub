<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_liveness_probe_returns_ok(): void
    {
        $response = $this->get('/healthz');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
            ]);
    }

    public function test_readiness_probe_returns_json_structure(): void
    {
        $response = $this->get('/readyz');

        $this->assertContains($response->status(), [200, 503]);
        $response->assertJsonStructure([
            'status',
            'checks' => [
                'database',
                'redis',
            ],
            'timestamp',
        ]);
    }
}
