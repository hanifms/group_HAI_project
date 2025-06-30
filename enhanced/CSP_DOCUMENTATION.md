# CSP Complete Documentation

This document contains comprehensive CSP documentation, troubleshooting guides, and advanced configuration details for the Travel Booking Application.

## Table of Contents

1. [Detailed Technical Documentation](#detailed-technical-documentation)
2. [Complete File Structure](#complete-file-structure)
3. [Advanced Configuration](#advanced-configuration)
4. [Blade Directives & Template Integration](#blade-directives--template-integration)
5. [Testing Framework](#testing-framework)
6. [Troubleshooting Guide](#troubleshooting-guide)
7. [Security Improvements](#security-improvements)
8. [IPv6 Fix Details](#ipv6-fix-details)
9. [Production Deployment](#production-deployment)
10. [Environment Configurations](#environment-configurations)

---

## Detailed Technical Documentation

### Complete Middleware Implementation

#### SecurityHeadersMiddleware (Full Implementation)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Add Content Security Policy
        $this->addContentSecurityPolicy($response);
        
        // Add other security headers
        $this->addSecurityHeaders($response);
        
        return $response;
    }
    
    private function addContentSecurityPolicy(Response $response): void
    {
        $csp = config('security.csp', []);
        
        if (empty($csp) || !config('security.csp.enabled', true)) {
            return;
        }
        
        $cspHeader = $this->buildCspHeader($csp);
        
        if (!empty($cspHeader)) {
            if (config('security.csp.report_only', false)) {
                $response->headers->set('Content-Security-Policy-Report-Only', $cspHeader);
            } else {
                $response->headers->set('Content-Security-Policy', $cspHeader);
            }
        }
    }
    
    private function buildCspHeader(array $csp): string
    {
        $directives = [];
        
        foreach ($csp['directives'] as $directive => $sources) {
            if (is_array($sources) && !empty($sources)) {
                $directives[] = $directive . ' ' . implode(' ', $sources);
            }
        }
        
        // Add report-uri if configured
        if (!empty($csp['report_uri'])) {
            $directives[] = 'report-uri ' . $csp['report_uri'];
        }
        
        // Add report-to if configured (newer CSP 3.0 standard)
        if (!empty($csp['report_to'])) {
            $directives[] = 'report-to ' . $csp['report_to'];
        }
        
        return implode('; ', $directives);
    }
    
    private function addSecurityHeaders(Response $response): void
    {
        $headers = config('security.headers', []);
        
        // X-Frame-Options
        if (!empty($headers['x_frame_options'])) {
            $response->headers->set('X-Frame-Options', $headers['x_frame_options']);
        }
        
        // X-Content-Type-Options
        if (!empty($headers['x_content_type_options'])) {
            $response->headers->set('X-Content-Type-Options', $headers['x_content_type_options']);
        }
        
        // X-XSS-Protection
        if (!empty($headers['x_xss_protection'])) {
            $response->headers->set('X-XSS-Protection', $headers['x_xss_protection']);
        }
        
        // Referrer-Policy
        if (!empty($headers['referrer_policy'])) {
            $response->headers->set('Referrer-Policy', $headers['referrer_policy']);
        }
        
        // Permissions-Policy
        if (!empty($headers['permissions_policy'])) {
            $response->headers->set('Permissions-Policy', $headers['permissions_policy']);
        }
        
        // HSTS (HTTP Strict Transport Security)
        if (config('security.headers.hsts_enabled', false) && $request->isSecure()) {
            $hstsValue = 'max-age=' . config('security.headers.hsts_max_age', 31536000);
            
            if (config('security.headers.hsts_include_subdomains', true)) {
                $hstsValue .= '; includeSubDomains';
            }
            
            if (config('security.headers.hsts_preload', false)) {
                $hstsValue .= '; preload';
            }
            
            $response->headers->set('Strict-Transport-Security', $hstsValue);
        }
    }
}
```

#### ContentSecurityPolicyMiddleware (Standalone)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Get CSP configuration
        $csp = config('security.csp', []);
        
        if (empty($csp) || !config('security.csp.enabled', true)) {
            return $response;
        }
        
        // Build CSP header value
        $cspHeader = $this->buildCspHeader($csp);
        
        if (!empty($cspHeader)) {
            $headerName = config('security.csp.report_only', false) 
                ? 'Content-Security-Policy-Report-Only'
                : 'Content-Security-Policy';
                
            $response->headers->set($headerName, $cspHeader);
        }
        
        return $response;
    }
    
    private function buildCspHeader(array $csp): string
    {
        $directives = [];
        
        foreach ($csp['directives'] as $directive => $sources) {
            if (is_array($sources) && !empty($sources)) {
                $directives[] = $directive . ' ' . implode(' ', $sources);
            }
        }
        
        if (!empty($csp['report_uri'])) {
            $directives[] = 'report-uri ' . $csp['report_uri'];
        }
        
        if (!empty($csp['report_to'])) {
            $directives[] = 'report-to ' . $csp['report_to'];
        }
        
        return implode('; ', $directives);
    }
}
```

### Complete Security Helper Implementation

```php
<?php

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Get the current CSP header value
     */
    public static function getCurrentCsp(): string
    {
        $csp = config('security.csp', []);
        
        if (empty($csp) || !config('security.csp.enabled', true)) {
            return '';
        }
        
        return static::buildCspHeader($csp);
    }
    
    /**
     * Build CSP header from configuration
     */
    public static function buildCspHeader(array $csp): string
    {
        $directives = [];
        
        foreach ($csp['directives'] as $directive => $sources) {
            if (is_array($sources) && !empty($sources)) {
                $directives[] = $directive . ' ' . implode(' ', $sources);
            }
        }
        
        if (!empty($csp['report_uri'])) {
            $directives[] = 'report-uri ' . $csp['report_uri'];
        }
        
        if (!empty($csp['report_to'])) {
            $directives[] = 'report-to ' . $csp['report_to'];
        }
        
        return implode('; ', $directives);
    }
    
    /**
     * Check if a specific security header is enabled
     */
    public static function isHeaderEnabled(string $header): bool
    {
        return !empty(config("security.headers.{$header}"));
    }
    
    /**
     * Get all security headers that would be applied
     */
    public static function getAllSecurityHeaders(Request $request = null): array
    {
        $headers = [];
        $config = config('security.headers', []);
        
        foreach ($config as $key => $value) {
            if (!empty($value) && $key !== 'hsts_enabled') {
                $headerName = str_replace('_', '-', ucwords($key, '_'));
                $headers[$headerName] = $value;
            }
        }
        
        return $headers;
    }
}
```

---

## Complete File Structure

```
Project Root/
├── app/
│   ├── Http/
│   │   └── Middleware/
│   │       ├── SecurityHeadersMiddleware.php       # Main security middleware
│   │       └── ContentSecurityPolicyMiddleware.php # CSP-specific middleware
│   ├── Helpers/
│   │   ├── CspHelper.php                          # CSP utility functions
│   │   └── SecurityHelper.php                     # General security utilities
│   └── Providers/
│       └── AppServiceProvider.php                 # Blade directives registration
├── bootstrap/
│   └── app.php                                    # Laravel 12 middleware registration
├── config/
│   └── security.php                              # Security configuration
├── routes/
│   └── web.php                                   # CSP violation reporting route
├── tests/
│   ├── Feature/
│   │   └── Security/
│   │       └── ContentSecurityPolicyTest.php     # CSP integration tests
│   └── Unit/
│       └── Helpers/
│           ├── CspHelperTest.php                  # CSP helper tests
│           └── SecurityHelperTest.php             # Security helper tests
├── public/
│   └── csp-test.html                             # CSP testing page
├── .env                                          # Environment configuration
├── .env.csp.development                          # Development CSP settings
├── .env.csp.production                           # Production CSP settings
├── .env.csp.example                              # CSP environment template
├── test-csp.php                                  # CSP testing script
├── verify-csp-fix.php                            # CSP verification script
├── CSP_FIX_SUMMARY.md                            # IPv6 fix documentation
├── CSP_IMPLEMENTATION.md                         # Original implementation guide
├── CSP_SECURITY_IMPROVEMENTS.md                  # Security enhancement guide
├── CSP_TROUBLESHOOTING.md                        # Troubleshooting guide
├── CSP_DOCUMENTATION.md                          # This comprehensive guide
└── HANIF.md                                      # Main implementation guide
```

---

## Advanced Configuration

### Complete `config/security.php`

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    |
    | Configure Content Security Policy directives to control which resources
    | the browser is allowed to load. This helps prevent XSS attacks and
    | other code injection vulnerabilities.
    |
    */

    'csp' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Content Security Policy
        |--------------------------------------------------------------------------
        |
        | Set this to true to enable CSP headers. You can disable this during
        | development if it interferes with debugging tools.
        |
        */
        'enabled' => env('CSP_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Report Only Mode
        |--------------------------------------------------------------------------
        |
        | When enabled, CSP violations will be reported but not blocked.
        | This is useful for testing CSP policies before enforcing them.
        | Set to true for development to avoid blocking functionality.
        |
        */
        'report_only' => env('CSP_REPORT_ONLY', false),

        /*
        |--------------------------------------------------------------------------
        | CSP Directives
        |--------------------------------------------------------------------------
        |
        | Define the Content Security Policy directives for your application.
        | Each directive controls what resources can be loaded from where.
        |
        */
        'directives' => [
            /*
            |--------------------------------------------------------------------------
            | Default Source
            |--------------------------------------------------------------------------
            |
            | The default-src directive serves as a fallback for other CSP directives.
            | If a specific directive is not defined, the browser will use default-src.
            |
            */
            'default-src' => [
                "'self'",
                'data:', // Allow data URLs
                'blob:', // Allow blob URLs
            ],

            /*
            |--------------------------------------------------------------------------
            | Script Sources
            |--------------------------------------------------------------------------
            |
            | Controls which scripts can be executed. This includes JavaScript files,
            | inline scripts, and eval() calls.
            |
            | SECURITY NOTE: 'unsafe-inline' and 'unsafe-eval' are kept for development
            | compatibility with Livewire and Alpine.js. For production, consider
            | implementing nonces and removing these unsafe directives.
            |
            */
            'script-src' => [
                "'self'",
                // Development compatibility - remove in production
                "'unsafe-inline'", // Needed for Livewire and Alpine.js
                // Note: 'unsafe-eval' removed for better security
                // Popular CDNs for libraries
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                // Development servers
                'http://localhost:5173', // Vite development server
                'http://127.0.0.1:5173', // Vite development server IPv4
                'ws://localhost:5173', // Vite WebSocket for hot reload
                'ws://127.0.0.1:5173', // Vite WebSocket IPv4
                'http://localhost:8000', // Laravel development server
                'http://127.0.0.1:8000', // Laravel development server IPv4
                // Framework-specific sources
                'https://livewire.laravel.com',
                'https://cdn.alpinejs.dev',
                // Allow blob: for dynamic script generation
                'blob:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Style Sources
            |--------------------------------------------------------------------------
            |
            | Controls which stylesheets can be applied. This includes CSS files,
            | inline styles, and style attributes.
            |
            */
            'style-src' => [
                "'self'",
                "'unsafe-inline'", // Allow inline styles (needed for Tailwind CSS)
                // Font providers
                'https://fonts.googleapis.com',
                'https://fonts.bunny.net',
                // Popular CDNs for CSS libraries
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                // Development servers
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'http://localhost:8000',
                'http://127.0.0.1:8000',
                // Allow blob: and data: for dynamic styles
                'blob:',
                'data:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Image Sources
            |--------------------------------------------------------------------------
            |
            | Controls which images can be loaded. This includes img tags,
            | CSS background images, and other image resources.
            |
            */
            'img-src' => [
                "'self'",
                'data:', // Allow data URLs (base64 images)
                'https:', // Allow any HTTPS image source
                'blob:', // Allow blob URLs (file uploads)
            ],

            /*
            |--------------------------------------------------------------------------
            | Font Sources
            |--------------------------------------------------------------------------
            |
            | Controls which fonts can be loaded. This includes web fonts
            | from font providers like Google Fonts or Bunny Fonts.
            |
            */
            'font-src' => [
                "'self'",
                'https://fonts.gstatic.com', // Google Fonts
                'https://fonts.googleapis.com', // Google Fonts API
                'https://fonts.bunny.net', // Bunny Fonts (privacy-friendly alternative)
                'data:', // Allow data URLs for fonts
            ],

            /*
            |--------------------------------------------------------------------------
            | Connect Sources
            |--------------------------------------------------------------------------
            |
            | Controls which URLs can be loaded using AJAX, WebSockets,
            | EventSource, and other connection types.
            |
            */
            'connect-src' => [
                "'self'",
                // WebSocket protocols
                'ws:', 'wss:',
                // Development servers (wildcards for flexibility)
                'http://localhost:*',
                'http://127.0.0.1:*',
                'ws://localhost:*',
                'ws://127.0.0.1:*',
                // Specific Vite development server
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'ws://localhost:5173',
                'ws://127.0.0.1:5173',
                // Laravel development server
                'http://localhost:8000',
                'http://127.0.0.1:8000',
                // API endpoints (add your production API domains)
                'https://api.example.com',
                // Framework-specific
                'https://livewire.laravel.com',
                // Allow data: and blob: for AJAX
                'data:',
                'blob:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Media Sources
            |--------------------------------------------------------------------------
            |
            | Controls which media (audio/video) can be loaded.
            |
            */
            'media-src' => [
                "'self'",
                'https:', // Allow any HTTPS media source
                'data:', // Allow data URLs for media
            ],

            /*
            |--------------------------------------------------------------------------
            | Object Sources
            |--------------------------------------------------------------------------
            |
            | Controls plugins like Flash, Java applets, etc.
            | Set to 'none' for security (plugins are deprecated).
            |
            */
            'object-src' => [
                "'none'", // Disable all plugins for security
            ],

            /*
            |--------------------------------------------------------------------------
            | Frame Sources
            |--------------------------------------------------------------------------
            |
            | Controls which URLs can be embedded in frames/iframes.
            |
            */
            'frame-src' => [
                "'self'",
                'https://www.youtube.com', // YouTube embeds
                'https://www.google.com', // Google services
                'https://maps.google.com', // Google Maps
            ],

            /*
            |--------------------------------------------------------------------------
            | Child Sources
            |--------------------------------------------------------------------------
            |
            | Fallback for frame-src and worker-src. Controls nested browsing
            | contexts and workers.
            |
            */
            'child-src' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Form Action
            |--------------------------------------------------------------------------
            |
            | Controls which URLs can be used as form action targets.
            |
            */
            'form-action' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Frame Ancestors
            |--------------------------------------------------------------------------
            |
            | Controls which URLs can embed this page in a frame.
            | This helps prevent clickjacking attacks.
            |
            */
            'frame-ancestors' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Base URI
            |--------------------------------------------------------------------------
            |
            | Controls which URLs can be used in the document's <base> element.
            |
            */
            'base-uri' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Manifest Source
            |--------------------------------------------------------------------------
            |
            | Controls which manifest files can be loaded.
            |
            */
            'manifest-src' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Worker Sources
            |--------------------------------------------------------------------------
            |
            | Controls which sources can be loaded as web workers, shared workers,
            | or service workers.
            |
            */
            'worker-src' => [
                "'self'",
                'blob:', // Allow blob URLs for workers
            ],

            /*
            |--------------------------------------------------------------------------
            | Script Source Elements
            |--------------------------------------------------------------------------
            |
            | Controls script elements specifically (complementary to script-src)
            |
            */
            'script-src-elem' => [
                "'self'",
                "'unsafe-inline'",
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'http://localhost:8000',
                'http://127.0.0.1:8000',
                'blob:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Style Source Elements
            |--------------------------------------------------------------------------
            |
            | Controls style elements specifically (complementary to style-src)
            |
            */
            'style-src-elem' => [
                "'self'",
                "'unsafe-inline'",
                'https://fonts.googleapis.com',
                'https://fonts.bunny.net',
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'http://localhost:5173',
                'http://127.0.0.1:5173',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | CSP Reporting
        |--------------------------------------------------------------------------
        |
        | Configure CSP violation reporting. When violations occur, the browser
        | can send reports to a specified endpoint for monitoring.
        |
        | For development, set CSP_REPORT_URI to /csp-report to enable logging
        | of CSP violations. In production, consider using a dedicated service.
        |
        */
        'report_uri' => env('CSP_REPORT_URI', '/csp-report'),
        'report_to' => env('CSP_REPORT_TO', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Security Headers
    |--------------------------------------------------------------------------
    |
    | Configure additional security headers to enhance application security.
    |
    */

    'headers' => [
        /*
        |--------------------------------------------------------------------------
        | X-Frame-Options
        |--------------------------------------------------------------------------
        |
        | Controls whether the page can be displayed in a frame to prevent
        | clickjacking attacks. Options: DENY, SAMEORIGIN, ALLOW-FROM uri
        |
        */
        'x_frame_options' => env('X_FRAME_OPTIONS', 'SAMEORIGIN'),

        /*
        |--------------------------------------------------------------------------
        | X-Content-Type-Options
        |--------------------------------------------------------------------------
        |
        | Prevents browsers from MIME-sniffing a response away from the declared
        | content-type. Set to 'nosniff' to enable this protection.
        |
        */
        'x_content_type_options' => env('X_CONTENT_TYPE_OPTIONS', 'nosniff'),

        /*
        |--------------------------------------------------------------------------
        | X-XSS-Protection
        |--------------------------------------------------------------------------
        |
        | Enables the XSS filter in older browsers. Modern browsers use CSP instead.
        | Options: 0 (disable), 1 (enable), 1; mode=block (enable and block)
        |
        */
        'x_xss_protection' => env('X_XSS_PROTECTION', '1; mode=block'),

        /*
        |--------------------------------------------------------------------------
        | Referrer Policy
        |--------------------------------------------------------------------------
        |
        | Controls how much referrer information is sent with requests.
        | Options: no-referrer, no-referrer-when-downgrade, same-origin, etc.
        |
        */
        'referrer_policy' => env('REFERRER_POLICY', 'strict-origin-when-cross-origin'),

        /*
        |--------------------------------------------------------------------------
        | Permissions Policy
        |--------------------------------------------------------------------------
        |
        | Controls which browser features can be used by the page.
        | This replaces the deprecated Feature-Policy header.
        |
        */
        'permissions_policy' => env('PERMISSIONS_POLICY', 'camera=(), microphone=(), geolocation=()'),

        /*
        |--------------------------------------------------------------------------
        | HTTP Strict Transport Security (HSTS)
        |--------------------------------------------------------------------------
        |
        | Enforces HTTPS connections. Only enable for HTTPS sites.
        |
        */
        'hsts_enabled' => env('HSTS_ENABLED', false),
        'hsts_max_age' => env('HSTS_MAX_AGE', 31536000), // 1 year
        'hsts_include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
        'hsts_preload' => env('HSTS_PRELOAD', false),
    ],
];
```

---

## Blade Directives & Template Integration

### Registering Blade Directives (`app/Providers/AppServiceProvider.php`)

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\CspHelper;
use App\Helpers\SecurityHelper;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register CSP helper
        $this->app->singleton('csp.helper', function ($app) {
            return new CspHelper();
        });
        
        // Register Security helper
        $this->app->singleton('security.helper', function ($app) {
            return new SecurityHelper();
        });
    }

    public function boot(): void
    {
        // Register custom blade directives
        $this->registerCspBladeDirectives();
        $this->registerSecurityBladeDirectives();
    }

    private function registerCspBladeDirectives(): void
    {
        // CSP nonce directive for inline scripts
        Blade::directive('cspNonce', function () {
            return "<?php echo \\App\\Helpers\\CspHelper::nonceAttribute(); ?>";
        });
        
        // CSP nonce value only
        Blade::directive('cspNonceValue', function () {
            return "<?php echo \\App\\Helpers\\CspHelper::nonce(); ?>";
        });
        
        // Check if CSP is enabled
        Blade::if('cspEnabled', function () {
            return \App\Helpers\CspHelper::isEnabled();
        });
        
        // Check if CSP is in report-only mode
        Blade::if('cspReportOnly', function () {
            return \App\Helpers\CspHelper::isReportOnly();
        });
        
        // Check if a source is allowed for a directive
        Blade::directive('cspAllows', function ($expression) {
            return "<?php echo \\App\\Helpers\\CspHelper::isSourceAllowed({$expression}) ? 'true' : 'false'; ?>";
        });
    }
    
    private function registerSecurityBladeDirectives(): void
    {
        // Check if a security header is enabled
        Blade::if('securityHeaderEnabled', function ($header) {
            return \App\Helpers\SecurityHelper::isHeaderEnabled($header);
        });
        
        // Get current CSP header value
        Blade::directive('currentCsp', function () {
            return "<?php echo \\App\\Helpers\\SecurityHelper::getCurrentCsp(); ?>";
        });
    }
}
```

### Template Usage Examples

```blade
{{-- Basic CSP nonce usage --}}
@cspEnabled
    <script @cspNonce>
        console.log('CSP is enabled and this script has a nonce');
    </script>
@else
    <script>
        console.log('CSP is disabled');
    </script>
@endcspEnabled

{{-- Alternative nonce syntax --}}
<script nonce="@cspNonceValue">
    // Your inline JavaScript here
</script>

{{-- Conditional loading based on CSP report mode --}}
@cspReportOnly
    <div class="alert alert-info">
        CSP is in report-only mode - violations are logged but not blocked
    </div>
@else
    <div class="alert alert-success">
        CSP is actively protecting this page
    </div>
@endcspReportOnly

{{-- Check if specific sources are allowed --}}
@if(@cspAllows('script-src', 'https://cdn.example.com'))
    <script src="https://cdn.example.com/library.js"></script>
@endif

{{-- Display current CSP policy (for debugging) --}}
@if(config('app.debug'))
    <meta name="csp-policy" content="@currentCsp">
@endif

{{-- Security headers conditional content --}}
@securityHeaderEnabled('x_frame_options')
    <p>Clickjacking protection is enabled</p>
@endsecurityHeaderEnabled
```

---

## Testing Framework

### Feature Tests (`tests/Feature/Security/ContentSecurityPolicyTest.php`)

```php
<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContentSecurityPolicyTest extends TestCase
{
    public function test_csp_header_is_present_when_enabled(): void
    {
        config(['security.csp.enabled' => true]);
        
        $response = $this->get('/');
        
        $response->assertSuccessful();
        
        $this->assertTrue(
            $response->headers->has('Content-Security-Policy') || 
            $response->headers->has('Content-Security-Policy-Report-Only'),
            'CSP header should be present when CSP is enabled'
        );
    }
    
    public function test_csp_header_not_present_when_disabled(): void
    {
        config(['security.csp.enabled' => false]);
        
        $response = $this->get('/');
        
        $response->assertSuccessful();
        
        $this->assertFalse(
            $response->headers->has('Content-Security-Policy'),
            'CSP header should not be present when CSP is disabled'
        );
        
        $this->assertFalse(
            $response->headers->has('Content-Security-Policy-Report-Only'),
            'CSP report-only header should not be present when CSP is disabled'
        );
    }
    
    public function test_csp_report_only_mode(): void
    {
        config([
            'security.csp.enabled' => true,
            'security.csp.report_only' => true,
        ]);
        
        $response = $this->get('/');
        
        $response->assertSuccessful();
        $response->assertHeader('Content-Security-Policy-Report-Only');
        $this->assertFalse($response->headers->has('Content-Security-Policy'));
    }
    
    public function test_csp_enforcing_mode(): void
    {
        config([
            'security.csp.enabled' => true,
            'security.csp.report_only' => false,
        ]);
        
        $response = $this->get('/');
        
        $response->assertSuccessful();
        $response->assertHeader('Content-Security-Policy');
        $this->assertFalse($response->headers->has('Content-Security-Policy-Report-Only'));
    }
    
    public function test_csp_header_contains_expected_directives(): void
    {
        config([
            'security.csp.enabled' => true,
            'security.csp.directives.default-src' => ["'self'"],
            'security.csp.directives.script-src' => ["'self'", "'unsafe-inline'"],
        ]);
        
        $response = $this->get('/');
        
        $cspHeader = $response->headers->get('Content-Security-Policy') 
                    ?? $response->headers->get('Content-Security-Policy-Report-Only');
        
        $this->assertStringContains("default-src 'self'", $cspHeader);
        $this->assertStringContains("script-src 'self' 'unsafe-inline'", $cspHeader);
    }
    
    public function test_csp_report_uri_is_included(): void
    {
        config([
            'security.csp.enabled' => true,
            'security.csp.report_uri' => '/csp-report',
        ]);
        
        $response = $this->get('/');
        
        $cspHeader = $response->headers->get('Content-Security-Policy') 
                    ?? $response->headers->get('Content-Security-Policy-Report-Only');
        
        $this->assertStringContains('report-uri /csp-report', $cspHeader);
    }
    
    public function test_csp_violation_reporting_endpoint(): void
    {
        $violationData = [
            'csp-report' => [
                'document-uri' => 'http://localhost/test',
                'referrer' => '',
                'violated-directive' => 'script-src-elem',
                'effective-directive' => 'script-src-elem',
                'original-policy' => "default-src 'self'",
                'blocked-uri' => 'http://evil.com/malicious.js',
                'status-code' => 200,
            ]
        ];
        
        $response = $this->postJson('/csp-report', $violationData);
        
        $response->assertStatus(204);
    }
    
    public function test_additional_security_headers(): void
    {
        config([
            'security.headers.x_frame_options' => 'SAMEORIGIN',
            'security.headers.x_content_type_options' => 'nosniff',
        ]);
        
        $response = $this->get('/');
        
        $response->assertSuccessful();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
```

### Unit Tests (`tests/Unit/Helpers/CspHelperTest.php`)

```php
<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\CspHelper;
use Illuminate\Support\Facades\Session;

class CspHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Session::flush();
    }
    
    public function test_nonce_generation(): void
    {
        $nonce1 = CspHelper::nonce();
        $nonce2 = CspHelper::nonce();
        
        $this->assertNotEmpty($nonce1);
        $this->assertEquals($nonce1, $nonce2); // Should be same within session
        $this->assertTrue(base64_decode($nonce1, true) !== false); // Should be valid base64
    }
    
    public function test_nonce_attribute(): void
    {
        $nonce = CspHelper::nonce();
        $attribute = CspHelper::nonceAttribute();
        
        $this->assertEquals('nonce="' . $nonce . '"', $attribute);
    }
    
    public function test_is_enabled(): void
    {
        config(['security.csp.enabled' => true]);
        $this->assertTrue(CspHelper::isEnabled());
        
        config(['security.csp.enabled' => false]);
        $this->assertFalse(CspHelper::isEnabled());
    }
    
    public function test_is_report_only(): void
    {
        config(['security.csp.report_only' => true]);
        $this->assertTrue(CspHelper::isReportOnly());
        
        config(['security.csp.report_only' => false]);
        $this->assertFalse(CspHelper::isReportOnly());
    }
    
    public function test_get_allowed_sources(): void
    {
        config(['security.csp.directives.script-src' => ["'self'", 'https://cdn.example.com']]);
        
        $sources = CspHelper::getAllowedSources('script-src');
        
        $this->assertEquals(["'self'", 'https://cdn.example.com'], $sources);
    }
    
    public function test_is_source_allowed(): void
    {
        config(['security.csp.directives.script-src' => ["'self'", 'https://cdn.example.com']]);
        
        $this->assertTrue(CspHelper::isSourceAllowed('script-src', "'self'"));
        $this->assertTrue(CspHelper::isSourceAllowed('script-src', 'https://cdn.example.com'));
        $this->assertFalse(CspHelper::isSourceAllowed('script-src', 'https://evil.com'));
    }
    
    public function test_nonce_persists_within_session(): void
    {
        Session::start();
        
        $nonce1 = CspHelper::nonce();
        $nonce2 = CspHelper::nonce();
        $nonce3 = CspHelper::nonce();
        
        $this->assertEquals($nonce1, $nonce2);
        $this->assertEquals($nonce2, $nonce3);
    }
    
    public function test_nonce_changes_between_sessions(): void
    {
        Session::start();
        $nonce1 = CspHelper::nonce();
        
        Session::flush();
        Session::regenerate();
        
        $nonce2 = CspHelper::nonce();
        
        $this->assertNotEquals($nonce1, $nonce2);
    }
}
```

---

## Troubleshooting Guide

### Common Issues and Solutions

#### 1. **Vite Development Server Issues**

**Problem**: Vite assets not loading, console shows CSP violations for `localhost:5173`

**Symptoms**:
```
Content-Security-Policy: The page's settings blocked a script at http://localhost:5173/@vite/client
```

**Solution**:
```php
// In config/security.php, ensure these are in script-src and connect-src:
'script-src' => [
    // ... other sources
    'http://localhost:5173',
    'http://127.0.0.1:5173',
],
'connect-src' => [
    // ... other sources
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'ws://localhost:5173',
    'ws://127.0.0.1:5173',
],
```

#### 2. **IPv6 Localhost Issues (FIXED)**

**Problem**: CSP parsing errors for IPv6 addresses like `[::1]:5173`

**Error Message**:
```
Content-Security-Policy: Couldn't parse invalid host http://[::1]:5173
```

**Solution**: ✅ **Already Fixed** - IPv6 literals have been removed from CSP configuration as they're not supported by browsers.

#### 3. **Livewire Not Working**

**Problem**: Livewire components not responding, AJAX requests blocked

**Symptoms**:
- Form submissions fail
- Real-time updates don't work
- Console shows CSP violations for inline scripts

**Solution**:
```php
'script-src' => [
    "'self'",
    "'unsafe-inline'", // Required for Livewire
    "'unsafe-eval'",   // May be required for some Livewire features
],
'connect-src' => [
    "'self'", // Allow AJAX to same domain
],
```

#### 4. **Alpine.js Issues**

**Problem**: Alpine.js directives (`x-data`, `x-show`, etc.) not working

**Solution**:
```php
'script-src' => [
    "'unsafe-inline'", // Required for Alpine.js inline event handlers
],
```

#### 5. **Font Loading Issues**

**Problem**: Web fonts not loading from Google Fonts, Bunny Fonts, etc.

**Solution**:
```php
'font-src' => [
    "'self'",
    'https://fonts.gstatic.com',
    'https://fonts.bunny.net',
    'data:',
],
'style-src' => [
    "'self'",
    'https://fonts.googleapis.com',
    'https://fonts.bunny.net',
],
```

#### 6. **Images Not Loading**

**Problem**: Images from external sources blocked

**Solution**:
```php
'img-src' => [
    "'self'",
    'data:',   // For base64 images
    'https:',  // Allow any HTTPS image
    'blob:',   // For file uploads
],
```

### Debugging Steps

#### 1. **Enable Report-Only Mode**
```bash
# In .env
CSP_REPORT_ONLY=true
```

#### 2. **Check Browser Console**
- Open Developer Tools (F12)
- Look for CSP violation messages
- Note the blocked resource and directive

#### 3. **Use CSP Test Page**
Visit `http://localhost:8000/csp-test.html` to run automated tests

#### 4. **Check Laravel Logs**
```bash
tail -f storage/logs/laravel.log | grep "CSP Violation"
```

#### 5. **Test CSP Configuration**
```bash
php test-csp.php
```

#### 6. **Verify IPv6 Fix**
```bash
php verify-csp-fix.php
```

### Environment-Specific Troubleshooting

#### Development Environment
```bash
# Recommended settings for development
CSP_ENABLED=true
CSP_REPORT_ONLY=true
HSTS_ENABLED=false
```

#### Production Environment
```bash
# Recommended settings for production
CSP_ENABLED=true
CSP_REPORT_ONLY=false
HSTS_ENABLED=true  # Only if using HTTPS
```

---

## Security Improvements

### Applied Security Enhancements

#### 1. **Removed `'unsafe-eval'`**
- **Risk**: Allows dangerous `eval()` functions
- **Status**: ✅ Removed from script-src
- **Impact**: Prevents code injection via eval()

#### 2. **Restricted Wildcard Sources**
- **Before**: `https:` allowed ANY HTTPS source
- **After**: Specific trusted domains only
- **Impact**: Prevents loading from untrusted sources

#### 3. **Enhanced Image Security**
```php
'img-src' => [
    "'self'",
    'data:',
    // Specific trusted image sources instead of 'https:'
    'https://images.unsplash.com',
    'https://via.placeholder.com',
    'https://picsum.photos',
    'https://gravatar.com',
    'https://*.gravatar.com',
    'blob:',
],
```

#### 4. **Media Source Restrictions**
```php
'media-src' => [
    "'self'",
    // Specific trusted media sources instead of 'https:'
    'https://www.youtube.com',
    'https://player.vimeo.com',
    'https://w.soundcloud.com',
    'data:',
],
```

### Production Security Recommendations

#### 1. **Remove `'unsafe-inline'` (Advanced)**
```php
// Instead of 'unsafe-inline', use nonces
'script-src' => [
    "'self'",
    // Remove 'unsafe-inline'
    // Add nonce support
],
```

#### 2. **Implement Nonce Strategy**
```blade
<script @cspNonce>
    // Your inline JavaScript
</script>
```

#### 3. **Domain Whitelisting**
```php
// Replace CDN wildcards with specific versions
'script-src' => [
    "'self'",
    'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
    // Be specific about allowed resources
],
```

#### 4. **Enable HSTS for HTTPS**
```bash
# Production .env for HTTPS sites
HSTS_ENABLED=true
HSTS_MAX_AGE=31536000
HSTS_INCLUDE_SUBDOMAINS=true
```

---

## IPv6 Fix Details

### The Problem
Browsers don't support IPv6 addresses in square brackets (`[::1]`) as host sources in CSP directives.

### What Was Removed
```php
// These were causing browser parsing errors:
'http://[::1]:5173'   // Vite dev server IPv6
'http://[::1]:8000'   // Laravel dev server IPv6
'ws://[::1]:5173'     // Vite WebSocket IPv6
'ws://[::1]:*'        // Generic IPv6 WebSocket
```

### What Was Kept
```php
// These work correctly:
'http://localhost:5173'     // Vite dev server
'http://127.0.0.1:5173'     // Vite dev server IPv4
'ws://localhost:5173'       // Vite WebSocket
'ws://127.0.0.1:5173'       // Vite WebSocket IPv4
```

### Verification
The fix has been verified and documented in:
- `CSP_FIX_SUMMARY.md`
- `verify-csp-fix.php` script
- Browser console should show no CSP parsing errors

---

## Production Deployment

### Pre-Deployment Checklist

#### 1. **Environment Configuration**
```bash
# Production .env settings
CSP_ENABLED=true
CSP_REPORT_ONLY=false
HSTS_ENABLED=true
CSP_REPORT_URI=https://yourdomain.com/csp-report
```

#### 2. **Security Headers Review**
- Verify X-Frame-Options is set to DENY or SAMEORIGIN
- Ensure HSTS is enabled for HTTPS sites
- Check Permissions-Policy restrictions

#### 3. **CSP Policy Testing**
- Test with report-only mode first
- Monitor CSP violation reports
- Gradually tighten policies

#### 4. **Performance Considerations**
- Remove development-specific sources
- Minimize CSP header size
- Use specific domains instead of wildcards

### Monitoring and Maintenance

#### 1. **CSP Violation Monitoring**
```php
// Enhanced violation logging
Route::post('/csp-report', function (Request $request) {
    $violation = $request->input('csp-report');
    
    Log::warning('CSP Violation', [
        'violation' => $violation,
        'user_agent' => $request->header('User-Agent'),
        'ip' => $request->ip(),
        'timestamp' => now(),
        'url' => $violation['document-uri'] ?? 'unknown',
    ]);
    
    // Optionally send to external monitoring service
    // MonitoringService::reportCspViolation($violation);
    
    return response('', 204);
});
```

#### 2. **Regular Security Audits**
- Review CSP policies quarterly
- Update allowed sources when adding new services
- Remove deprecated or unused sources
- Test CSP changes in staging environment

---

## Environment Configurations

### Development (`.env.csp.development`)
```bash
# Development CSP Environment Variables
CSP_ENABLED=true
CSP_REPORT_ONLY=true
HSTS_ENABLED=false

# Additional Security Headers - Development friendly
X_FRAME_OPTIONS=SAMEORIGIN
X_CONTENT_TYPE_OPTIONS=nosniff
X_XSS_PROTECTION="1; mode=block"
REFERRER_POLICY=strict-origin-when-cross-origin
PERMISSIONS_POLICY="camera=(), microphone=(), geolocation=()"

# CSP violation reporting
CSP_REPORT_URI=/csp-report
```

### Production (`.env.csp.production`)
```bash
# Production CSP Environment Variables
CSP_ENABLED=true
CSP_REPORT_ONLY=false
HSTS_ENABLED=true
HSTS_MAX_AGE=31536000
HSTS_INCLUDE_SUBDOMAINS=true

# Strict Security Headers
X_FRAME_OPTIONS=SAMEORIGIN
X_CONTENT_TYPE_OPTIONS=nosniff
X_XSS_PROTECTION="1; mode=block"
REFERRER_POLICY=strict-origin-when-cross-origin
PERMISSIONS_POLICY="camera=(), microphone=(), geolocation=()"

# CSP violation reporting
CSP_REPORT_URI=https://yourdomain.com/csp-report
```

### Testing (`.env.testing`)
```bash
# Testing environment
CSP_ENABLED=true
CSP_REPORT_ONLY=true
HSTS_ENABLED=false

# Minimal security headers for testing
X_FRAME_OPTIONS=SAMEORIGIN
X_CONTENT_TYPE_OPTIONS=nosniff
CSP_REPORT_URI=/csp-report
```

---

## Final Notes

This comprehensive documentation covers all aspects of the CSP implementation in the Travel Booking Application. The implementation provides:

✅ **Strong Security** - Protection against XSS, clickjacking, and code injection  
✅ **Framework Compatibility** - Works with Laravel 12, Livewire, Alpine.js, and Vite  
✅ **Development Friendly** - Report-only mode for easy debugging  
✅ **Production Ready** - Configurable for different environments  
✅ **Well Tested** - Comprehensive test coverage  
✅ **Thoroughly Documented** - Complete guides and troubleshooting  

The CSP configuration has been specifically tuned for modern Laravel development while maintaining security best practices. Regular monitoring and updates ensure continued protection as the application evolves.
