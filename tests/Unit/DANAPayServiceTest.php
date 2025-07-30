<?php

namespace Tests\Unit;

use Tests\TestCase;
use Otnansirk\Dana\Services\DANAPayService;

class DANAPayServiceTest extends TestCase
{
    public function test_can_generate_oauth_url(): void
    {
        $service = new DANAPayService();
        $url = $service->generateOauthUrl('WEB', 'https://example.com/callback');
        
        $this->assertStringContainsString('https://m.sandbox.dana.id/d/portal/oauth', $url);
        $this->assertStringContainsString('clientId=test_client_id', $url);
        $this->assertStringContainsString('terminalType=WEB', $url);
        $this->assertStringContainsString('redirectUrl=https%3A%2F%2Fexample.com%2Fcallback', $url);
    }

    public function test_can_generate_oauth_url_with_different_terminal_types(): void
    {
        $service = new DANAPayService();
        
        $url1 = $service->generateOauthUrl('APP', 'https://example.com/callback');
        $this->assertStringContainsString('terminalType=APP', $url1);
        
        $url2 = $service->generateOauthUrl('WAP', 'https://example.com/callback');
        $this->assertStringContainsString('terminalType=WAP', $url2);
        
        $url3 = $service->generateOauthUrl('SYSTEM', 'https://example.com/callback');
        $this->assertStringContainsString('terminalType=SYSTEM', $url3);
    }

    public function test_can_create_service_instance(): void
    {
        $service = new DANAPayService();
        $this->assertInstanceOf(DANAPayService::class, $service);
    }
} 