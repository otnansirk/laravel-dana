<?php

namespace Tests\Feature;

use Tests\TestCase;
use Otnansirk\Dana\Services\DANAPayService;

class DANAPayFacadeTest extends TestCase
{
    public function test_can_create_dana_pay_service(): void
    {
        $service = new DANAPayService();
        $this->assertInstanceOf(DANAPayService::class, $service);
    }

    public function test_can_generate_oauth_url_via_service(): void
    {
        $service = new DANAPayService();
        $url = $service->generateOauthUrl('WEB', 'https://example.com/callback');
        
        $this->assertStringContainsString('https://m.sandbox.dana.id/d/portal/oauth', $url);
        $this->assertStringContainsString('clientId=test_client_id', $url);
        $this->assertStringContainsString('terminalType=WEB', $url);
        $this->assertStringContainsString('redirectUrl=https%3A%2F%2Fexample.com%2Fcallback', $url);
    }

    public function test_can_access_service_via_container(): void
    {
        $service = app('DANAPay');
        $this->assertInstanceOf(DANAPayService::class, $service);
    }
} 