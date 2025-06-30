<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\CspHelper;

class CspHelperTest extends TestCase
{
    /**
     * Test nonce generation
     */
    public function test_nonce_generation(): void
    {
        $nonce1 = CspHelper::nonce();
        $nonce2 = CspHelper::nonce();

        // Nonce should be consistent within the same session
        $this->assertEquals($nonce1, $nonce2);

        // Nonce should not be empty
        $this->assertNotEmpty($nonce1);

        // Nonce should be base64 encoded
        $this->assertTrue(base64_decode($nonce1, true) !== false);
    }

    /**
     * Test nonce attribute generation
     */
    public function test_nonce_attribute_generation(): void
    {
        $attribute = CspHelper::nonceAttribute();

        $this->assertStringStartsWith('nonce="', $attribute);
        $this->assertStringEndsWith('"', $attribute);

        // Extract nonce value from attribute
        $nonce = substr($attribute, 7, -1); // Remove 'nonce="' and '"'
        $this->assertEquals($nonce, CspHelper::nonce());
    }

    /**
     * Test CSP enabled check
     */
    public function test_csp_enabled_check(): void
    {
        // Default should be enabled
        $this->assertTrue(CspHelper::isEnabled());

        // Test with disabled configuration
        config(['security.csp.enabled' => false]);
        $this->assertFalse(CspHelper::isEnabled());

        // Test with enabled configuration
        config(['security.csp.enabled' => true]);
        $this->assertTrue(CspHelper::isEnabled());
    }

    /**
     * Test CSP report-only check
     */
    public function test_csp_report_only_check(): void
    {
        // Default should be false
        $this->assertFalse(CspHelper::isReportOnly());

        // Test with report-only enabled
        config(['security.csp.report_only' => true]);
        $this->assertTrue(CspHelper::isReportOnly());

        // Test with report-only disabled
        config(['security.csp.report_only' => false]);
        $this->assertFalse(CspHelper::isReportOnly());
    }

    /**
     * Test getting allowed sources for a directive
     */
    public function test_get_allowed_sources(): void
    {
        $testSources = ["'self'", 'https://example.com', 'data:'];
        config(['security.csp.directives.script-src' => $testSources]);

        $allowedSources = CspHelper::getAllowedSources('script-src');

        $this->assertEquals($testSources, $allowedSources);
    }

    /**
     * Test checking if a source is allowed
     */
    public function test_is_source_allowed(): void
    {
        config(['security.csp.directives.script-src' => ["'self'", 'https://example.com']]);

        // Test allowed sources
        $this->assertTrue(CspHelper::isSourceAllowed('script-src', "'self'"));
        $this->assertTrue(CspHelper::isSourceAllowed('script-src', 'https://example.com'));

        // Test disallowed source
        $this->assertFalse(CspHelper::isSourceAllowed('script-src', 'https://malicious.com'));

        // Test with wildcard
        config(['security.csp.directives.img-src' => ['*']]);
        $this->assertTrue(CspHelper::isSourceAllowed('img-src', 'https://any-domain.com'));
    }

    /**
     * Test getting allowed sources for non-existent directive
     */
    public function test_get_allowed_sources_for_non_existent_directive(): void
    {
        $allowedSources = CspHelper::getAllowedSources('non-existent-directive');

        $this->assertEquals([], $allowedSources);
    }

    /**
     * Test source checking for non-existent directive
     */
    public function test_is_source_allowed_for_non_existent_directive(): void
    {
        $this->assertFalse(CspHelper::isSourceAllowed('non-existent-directive', 'https://example.com'));
    }
}
