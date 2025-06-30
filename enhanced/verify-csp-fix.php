#!/usr/bin/env php
<?php

/**
 * Simple CSP Configuration Verification Script
 *
 * This script verifies that the IPv6 CSP issues have been fixed.
 */

echo "======================================================================\n";
echo "CSP IPv6 Fix Verification\n";
echo "======================================================================\n\n";

// Mock env() function for standalone script
if (!function_exists('env')) {
    function env($key, $default = null) {
        static $envValues = null;

        if ($envValues === null) {
            $envValues = [];
            $envFile = __DIR__ . '/.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                        [$key, $value] = explode('=', $line, 2);
                        $envValues[trim($key)] = trim($value, '"\'');
                    }
                }
            }
        }

        return $envValues[$key] ?? $default;
    }
}

// Load security configuration directly
$configFile = __DIR__ . '/config/security.php';
if (!file_exists($configFile)) {
    echo "❌ Error: security.php config file not found!\n";
    exit(1);
}

try {
    $config = require $configFile;
    $csp = $config['csp'];
    $directives = $csp['directives'];
    echo "✅ Security configuration loaded successfully\n\n";
} catch (Exception $e) {
    echo "❌ Error loading configuration: " . $e->getMessage() . "\n";
    exit(1);
}

// Check for IPv6 issues (should be none)
$ipv6Issues = [];
$directivesToCheck = ['script-src', 'style-src', 'connect-src', 'script-src-elem', 'style-src-elem'];

foreach ($directivesToCheck as $directive) {
    if (isset($directives[$directive])) {
        foreach ($directives[$directive] as $source) {
            if (strpos($source, '[::1]') !== false) {
                $ipv6Issues[] = "$directive: $source";
            }
        }
    }
}

echo "=== IPv6 CSP Issues Check ===\n";
if (empty($ipv6Issues)) {
    echo "✅ No IPv6 literals found in CSP configuration\n";
    echo "   (This is correct - browsers don't support [::1] format in CSP)\n";
} else {
    echo "❌ IPv6 literals still found in CSP:\n";
    foreach ($ipv6Issues as $issue) {
        echo "   - $issue\n";
    }
    echo "   These should be removed as they cause browser parsing errors.\n";
}

echo "\n=== Vite Development Support ===\n";
$viteSupport = [
    'localhost:5173' => false,
    '127.0.0.1:5173' => false,
    'WebSocket support' => false,
];

// Check script-src for Vite
foreach ($directives['script-src'] as $source) {
    if (strpos($source, 'localhost:5173') !== false) {
        $viteSupport['localhost:5173'] = true;
    }
    if (strpos($source, '127.0.0.1:5173') !== false) {
        $viteSupport['127.0.0.1:5173'] = true;
    }
}

// Check connect-src for WebSocket
foreach ($directives['connect-src'] as $source) {
    if (strpos($source, 'ws://localhost') !== false || strpos($source, 'ws://127.0.0.1') !== false) {
        $viteSupport['WebSocket support'] = true;
    }
}

foreach ($viteSupport as $feature => $supported) {
    echo ($supported ? "✅" : "❌") . " $feature: " . ($supported ? "Supported" : "Missing") . "\n";
}

echo "\n=== CSP Reporting ===\n";
$reportUri = $csp['report_uri'] ?? null;
if ($reportUri) {
    echo "✅ Report URI configured: $reportUri\n";
} else {
    echo "❌ No report URI configured\n";
}

echo "\n=== Environment Variables ===\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);

    $envChecks = [
        'CSP_ENABLED' => preg_match('/CSP_ENABLED\s*=\s*true/i', $envContent),
        'CSP_REPORT_ONLY' => preg_match('/CSP_REPORT_ONLY\s*=\s*true/i', $envContent),
        'CSP_REPORT_URI' => preg_match('/CSP_REPORT_URI\s*=\s*.+/', $envContent),
    ];

    foreach ($envChecks as $setting => $found) {
        echo ($found ? "✅" : "❌") . " $setting: " . ($found ? "Set" : "Not set") . "\n";
    }
} else {
    echo "❌ .env file not found\n";
}

echo "\n=== Critical Framework Support ===\n";
$frameworkSupport = [
    'Livewire unsafe-inline' => in_array("'unsafe-inline'", $directives['script-src']),
    'Livewire unsafe-eval' => in_array("'unsafe-eval'", $directives['script-src']),
    'Alpine.js support' => in_array("'unsafe-inline'", $directives['script-src']),
    'Inline styles' => in_array("'unsafe-inline'", $directives['style-src']),
    'Blob URLs' => in_array('blob:', $directives['script-src']),
];

foreach ($frameworkSupport as $feature => $supported) {
    echo ($supported ? "✅" : "❌") . " $feature: " . ($supported ? "Enabled" : "Disabled") . "\n";
}

echo "\n=== Summary ===\n";

if (empty($ipv6Issues)) {
    echo "✅ IPv6 CSP parsing issues have been resolved\n";
} else {
    echo "❌ IPv6 CSP issues still present - remove [::1] entries\n";
}

if ($viteSupport['localhost:5173'] && $viteSupport['127.0.0.1:5173']) {
    echo "✅ Vite development support is properly configured\n";
} else {
    echo "❌ Vite development support needs attention\n";
}

if ($reportUri) {
    echo "✅ CSP reporting is configured\n";
} else {
    echo "❌ Configure CSP_REPORT_URI for violation reporting\n";
}

echo "\n=== Next Steps ===\n";
echo "1. Start your development servers:\n";
echo "   npm run dev     (Vite on port 5173)\n";
echo "   php artisan serve (Laravel on port 8000)\n\n";
echo "2. Open your application in browser\n";
echo "3. Check browser console - should see no CSP parsing errors\n";
echo "4. Check Laravel logs for CSP violations at /csp-report\n\n";

echo "======================================================================\n";
echo "Verification complete!\n";
echo "======================================================================\n";
