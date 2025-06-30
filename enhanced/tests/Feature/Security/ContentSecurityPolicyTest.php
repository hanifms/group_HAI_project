<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentSecurityPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that CSP header is present on responses
     */
    public function test_csp_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        // Check if CSP header is present (either enforcing or report-only)
        $this->assertTrue(
            $response->headers->has('Content-Security-Policy') ||
            $response->headers->has('Content-Security-Policy-Report-Only'),
            'CSP header should be present on response'
        );
    }

    /**
     * Test CSP header contains expected directives
     */
    public function test_csp_header_contains_expected_directives(): void
    {
        $response = $this->get('/');

        $cspHeader = $response->headers->get('Content-Security-Policy') ??
                    $response->headers->get('Content-Security-Policy-Report-Only');

        $this->assertNotNull($cspHeader, 'CSP header should be present');

        // Check for key directives
        $this->assertStringContainsString('default-src', $cspHeader);
        $this->assertStringContainsString('script-src', $cspHeader);
        $this->assertStringContainsString('style-src', $cspHeader);
        $this->assertStringContainsString('img-src', $cspHeader);
        $this->assertStringContainsString("'self'", $cspHeader);
    }

    /**
     * Test additional security headers are present
     */
    public function test_additional_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        // Check for additional security headers
        $this->assertTrue(
            $response->headers->has('X-Frame-Options'),
            'X-Frame-Options header should be present'
        );

        $this->assertTrue(
            $response->headers->has('X-Content-Type-Options'),
            'X-Content-Type-Options header should be present'
        );

        $this->assertTrue(
            $response->headers->has('X-XSS-Protection'),
            'X-XSS-Protection header should be present'
        );

        $this->assertTrue(
            $response->headers->has('Referrer-Policy'),
            'Referrer-Policy header should be present'
        );
    }

    /**
     * Test X-Frame-Options header value
     */
    public function test_x_frame_options_header_value(): void
    {
        $response = $this->get('/');

        $this->assertEquals(
            'SAMEORIGIN',
            $response->headers->get('X-Frame-Options'),
            'X-Frame-Options should be set to SAMEORIGIN'
        );
    }

    /**
     * Test X-Content-Type-Options header value
     */
    public function test_x_content_type_options_header_value(): void
    {
        $response = $this->get('/');

        $this->assertEquals(
            'nosniff',
            $response->headers->get('X-Content-Type-Options'),
            'X-Content-Type-Options should be set to nosniff'
        );
    }

    /**
     * Test CSP can be disabled via configuration
     */
    public function test_csp_can_be_disabled(): void
    {
        config(['security.csp.enabled' => false]);

        $response = $this->get('/');

        $response->assertSuccessful();

        // CSP headers should not be present when disabled
        $this->assertFalse(
            $response->headers->has('Content-Security-Policy'),
            'CSP header should not be present when disabled'
        );

        $this->assertFalse(
            $response->headers->has('Content-Security-Policy-Report-Only'),
            'CSP Report-Only header should not be present when disabled'
        );
    }

    /**
     * Test CSP report-only mode
     */
    public function test_csp_report_only_mode(): void
    {
        config(['security.csp.report_only' => true]);

        $response = $this->get('/');

        $response->assertSuccessful();

        // Should have report-only header
        $this->assertTrue(
            $response->headers->has('Content-Security-Policy-Report-Only'),
            'CSP Report-Only header should be present in report-only mode'
        );

        // Should not have enforcing header
        $this->assertFalse(
            $response->headers->has('Content-Security-Policy'),
            'CSP enforcing header should not be present in report-only mode'
        );
    }

    /**
     * Test CSP headers on authenticated routes
     */
    public function test_csp_headers_on_authenticated_routes(): void
    {
        $user = \App\Models\User::factory()->withUserRole()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertSuccessful();

        // CSP should be present on authenticated routes too
        $this->assertTrue(
            $response->headers->has('Content-Security-Policy') ||
            $response->headers->has('Content-Security-Policy-Report-Only'),
            'CSP header should be present on authenticated routes'
        );
    }

    /**
     * Test CSP headers on admin routes
     */
    public function test_csp_headers_on_admin_routes(): void
    {
        // Seed roles first
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $admin = \App\Models\User::factory()->withAdminRole()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertSuccessful();

        // CSP should be present on admin routes too
        $this->assertTrue(
            $response->headers->has('Content-Security-Policy') ||
            $response->headers->has('Content-Security-Policy-Report-Only'),
            'CSP header should be present on admin routes'
        );
    }
}
