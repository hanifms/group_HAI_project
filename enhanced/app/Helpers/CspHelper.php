<?php

namespace App\Helpers;

/**
 * Content Security Policy Helper
 *
 * This helper provides utilities for working with CSP,
 * including nonce generation for inline scripts and styles.
 */
class CspHelper
{
    /**
     * Generate a cryptographic nonce for CSP
     */
    public static function nonce(): string
    {
        if (!session()->has('csp_nonce')) {
            session(['csp_nonce' => base64_encode(random_bytes(16))]);
        }

        return session('csp_nonce');
    }

    /**
     * Get the CSP nonce for use in HTML attributes
     */
    public static function nonceAttribute(): string
    {
        return 'nonce="' . self::nonce() . '"';
    }

    /**
     * Check if CSP is enabled
     */
    public static function isEnabled(): bool
    {
        return config('security.csp.enabled', true);
    }

    /**
     * Check if CSP is in report-only mode
     */
    public static function isReportOnly(): bool
    {
        return config('security.csp.report_only', false);
    }

    /**
     * Get allowed sources for a specific directive
     */
    public static function getAllowedSources(string $directive): array
    {
        return config("security.csp.directives.{$directive}", []);
    }

    /**
     * Check if a source is allowed for a directive
     */
    public static function isSourceAllowed(string $directive, string $source): bool
    {
        $allowedSources = self::getAllowedSources($directive);

        return in_array($source, $allowedSources) ||
               in_array('*', $allowedSources);
    }
}
