#!/usr/bin/env php
<?php

/**
 * CSP Testing Script for Travel Booking Application
 *
 * This script helps test the CSP configuration and provides debugging information.
 * Run this script to check your CSP setup and get recommendations.
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

echo "=============================================================================\n";
echo "CSP Configuration Test for Travel Booking Application\n";
echo "=============================================================================\n\n";

// Check if CSP is enabled
$cspEnabled = config('security.csp.enabled', true);
$reportOnly = config('security.csp.report_only', false);

echo "CSP Status:\n";
echo "- Enabled: " . ($cspEnabled ? 'YES' : 'NO') . "\n";
echo "- Report Only: " . ($reportOnly ? 'YES (violations reported but not blocked)' : 'NO (violations blocked)') . "\n\n";

if (!$cspEnabled) {
    echo "⚠️  CSP is disabled. Set CSP_ENABLED=true in your .env file to enable it.\n\n";
    exit(1);
}

// Get CSP directives
$directives = config('security.csp.directives', []);

echo "CSP Directives:\n";
echo "===============\n";

foreach ($directives as $directive => $sources) {
    echo sprintf("%-20s: %s\n", $directive, implode(' ', $sources));
}

echo "\nEnvironment Variables:\n";
echo "=====================\n";

$envVars = [
    'CSP_ENABLED' => env('CSP_ENABLED', 'not set'),
    'CSP_REPORT_ONLY' => env('CSP_REPORT_ONLY', 'not set'),
    'CSP_REPORT_URI' => env('CSP_REPORT_URI', 'not set'),
    'APP_ENV' => env('APP_ENV', 'not set'),
    'APP_DEBUG' => env('APP_DEBUG', 'not set'),
];

foreach ($envVars as $var => $value) {
    echo sprintf("%-20s: %s\n", $var, $value);
}

echo "\nRecommendations:\n";
echo "================\n";

if ($reportOnly) {
    echo "✅ CSP is in report-only mode. Good for development and testing.\n";
    echo "   Check browser console for CSP violation reports.\n";
} else {
    echo "⚠️  CSP is in enforcing mode. Violations will be blocked.\n";
    echo "   Consider using report-only mode during development: CSP_REPORT_ONLY=true\n";
}

if (app()->environment('local', 'development')) {
    echo "✅ Development environment detected.\n";
    if (!$reportOnly) {
        echo "   Recommendation: Set CSP_REPORT_ONLY=true for development.\n";
    }
} else {
    echo "🔒 Production environment detected.\n";
    if ($reportOnly) {
        echo "   Recommendation: Set CSP_REPORT_ONLY=false for production.\n";
    }
}

// Check for common issues
$scriptSrc = $directives['script-src'] ?? [];
$styleSrc = $directives['style-src'] ?? [];

echo "\nCommon Framework Requirements:\n";
echo "=============================\n";

$checks = [
    'Livewire support' => in_array("'unsafe-inline'", $scriptSrc) || in_array("'unsafe-eval'", $scriptSrc),
    'Alpine.js support' => in_array("'unsafe-inline'", $scriptSrc),
    'Vite development (IPv4)' => in_array('http://localhost:5173', $scriptSrc) || in_array('http://127.0.0.1:5173', $scriptSrc),
    'IPv6 literals in CSP' => in_array('http://[::1]:5173', $scriptSrc),
    'Inline styles' => in_array("'unsafe-inline'", $styleSrc),
    'Fonts support' => !empty(array_intersect(['https://fonts.bunny.net', 'https://fonts.googleapis.com'], $styleSrc)),
];

foreach ($checks as $check => $status) {
    if ($check === 'IPv6 literals in CSP') {
        echo sprintf("%-25s: %s\n", $check, $status ? '❌ FOUND (should be removed)' : '✅ NOT FOUND (correct)');
    } else {
        echo sprintf("%-25s: %s\n", $check, $status ? '✅ SUPPORTED' : '❌ NOT SUPPORTED');
    }
}

echo "\nTroubleshooting:\n";
echo "================\n";
echo "1. If UI is not working:\n";
echo "   - Set CSP_REPORT_ONLY=true in .env\n";
echo "   - Check browser console for CSP violations\n";
echo "   - Reload the page and test functionality\n\n";

echo "2. For Vite development:\n";
echo "   - Make sure 'npm run dev' is running\n";
echo "   - Check that localhost:5173 and 127.0.0.1:5173 are in script-src and connect-src\n";
echo "   - IPv6 [::1] is NOT supported in CSP - removed to fix parsing errors\n\n";

echo "3. For Livewire issues:\n";
echo "   - Ensure 'unsafe-inline' and 'unsafe-eval' are in script-src\n";
echo "   - Check that AJAX requests are allowed in connect-src\n\n";

echo "4. IPv6 localhost considerations:\n";
echo "   - Browsers reject [::1] format in CSP host sources\n";
echo "   - Use localhost or 127.0.0.1 instead for development\n";
echo "   - Vite should bind to IPv4 addresses for CSP compatibility\n\n";

echo "5. CSP Reports:\n";
echo "   - Check /csp-report endpoint receives violation reports\n";
echo "   - View Laravel logs for CSP violation details\n\n";

echo "Testing completed!\n";
echo "=============================================================================\n";
