<?php

namespace Tests\Feature;

use Tests\TestCase;

class PwaTest extends TestCase
{
    public function test_manifest_is_accessible_and_valid_json(): void
    {
        $response = $this->get('/manifest.json');

        $response->assertStatus(200);
        $manifest = json_decode($response->getContent(), true);

        $this->assertIsArray($manifest);
        $this->assertEquals('Swap Hub', $manifest['short_name']);
        $this->assertEquals('standalone', $manifest['display']);
        $this->assertArrayHasKey('icons', $manifest);
        $this->assertNotEmpty($manifest['icons']);
        $this->assertEquals('#0d9488', $manifest['theme_color']);
        $this->assertEquals('#0f172a', $manifest['background_color']);
    }

    public function test_manifest_webmanifest_is_accessible(): void
    {
        $response = $this->get('/manifest.webmanifest');
        $response->assertStatus(200);
    }

    public function test_service_worker_script_is_accessible(): void
    {
        $response = $this->get('/sw.js');

        $response->assertStatus(200);
        $this->assertStringContainsString('CACHE_VERSION', $response->getContent());
        $this->assertStringContainsString('addEventListener(\'install\'', $response->getContent());
        $this->assertStringContainsString('addEventListener(\'fetch\'', $response->getContent());
    }

    public function test_offline_fallback_page_is_accessible(): void
    {
        $response = $this->get('/offline');
        $response->assertStatus(200);
        $response->assertSee('Anda Sedang Offline');

        $this->assertFileExists(public_path('offline.html'));
        $content = file_get_contents(public_path('offline.html'));
        $this->assertStringContainsString('Anda Sedang Offline', $content);
    }

    public function test_pwa_icons_exist_on_disk(): void
    {
        $icons = [
            'icons/icon-72x72.png',
            'icons/icon-96x96.png',
            'icons/icon-128x128.png',
            'icons/icon-144x144.png',
            'icons/icon-152x152.png',
            'icons/icon-192x192.png',
            'icons/icon-384x384.png',
            'icons/icon-512x512.png',
            'icons/icon-512x512-maskable.png',
            'icons/apple-touch-icon.png',
        ];

        foreach ($icons as $icon) {
            $this->assertFileExists(public_path($icon));
            $this->assertGreaterThan(0, filesize(public_path($icon)));
        }
    }

    public function test_pages_contain_pwa_meta_tags(): void
    {
        $welcome = $this->get('/');
        $welcome->assertStatus(200);
        $welcome->assertSee('rel="manifest"', false);
        $welcome->assertSee('apple-touch-icon', false);

        $login = $this->get('/login');
        $login->assertStatus(200);
        $login->assertSee('rel="manifest"', false);
        $login->assertSee('apple-touch-icon', false);
    }
}
