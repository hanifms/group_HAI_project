# CSP Implementation Guide for Travel Booking Application

## Quick Setup Overview

### 1. Files That Make CSP Work

```
app/
├── Http/Middleware/
│   └── SecurityHeadersMiddleware.php     # Main security guard
├── Helpers/
│   └── CspHelper.php                     # Helper utilities
config/
└── security.php                         # CSP rules & configuration
.env                                      # Environment settings
routes/
└── web.php                              # CSP violation reporting route
```

### 2. Environment Configuration (`.env`)

```bash
# Turn CSP on/off
CSP_ENABLED=true

# Development: true (reports violations but doesn't block)
# Production: false (actually blocks violations)
CSP_REPORT_ONLY=true

# Where to report violations
CSP_REPORT_URI=/csp-report
```

### 3. Core Configuration (`config/security.php`)

This file contains the **rules** for what's allowed:

```php
return [
    'csp' => [
        'enabled' => env('CSP_ENABLED', true),
        'report_only' => env('CSP_REPORT_ONLY', false),
        
        'directives' => [
            // Default policy - fallback for everything
            'default-src' => ["'self'", 'data:', 'blob:'],
            
            // JavaScript sources
            'script-src' => [
                "'self'",
                "'unsafe-inline'",    // Needed for Livewire/Alpine.js
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'http://localhost:5173',  // Vite dev server
                'http://127.0.0.1:5173',
                'blob:',
            ],
            
            // CSS/Style sources
            'style-src' => [
                "'self'",
                "'unsafe-inline'",    // Needed for Tailwind/inline styles
                'https://fonts.bunny.net',
                'https://fonts.googleapis.com',
                'http://localhost:5173',
                'blob:', 'data:',
            ],
            
            // Image sources
            'img-src' => ["'self'", 'data:', 'https:', 'blob:'],
            
            // Font sources
            'font-src' => [
                "'self'",
                'https://fonts.gstatic.com',
                'https://fonts.bunny.net',
                'data:',
            ],
            
            // WebSocket/AJAX connections
            'connect-src' => [
                "'self'",
                'ws://localhost:5173',    // Vite WebSocket
                'ws://127.0.0.1:5173',
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'data:', 'blob:',
            ],
        ],
        
        'report_uri' => env('CSP_REPORT_URI', '/csp-report'),
    ],
];
```

### 4. Main Security Middleware (`app/Http/Middleware/SecurityHeadersMiddleware.php`)

This automatically applies CSP to every page:

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
        
        // Add CSP headers
        $this->addContentSecurityPolicy($response);
        
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
        
        if (!empty($csp['report_uri'])) {
            $directives[] = 'report-uri ' . $csp['report_uri'];
        }
        
        return implode('; ', $directives);
    }
}
```

### 5. Helper Utilities (`app/Helpers/CspHelper.php`)

Useful functions for working with CSP:

```php
<?php

namespace App\Helpers;

class CspHelper
{
    public static function nonce(): string
    {
        if (!session()->has('csp_nonce')) {
            session(['csp_nonce' => base64_encode(random_bytes(16))]);
        }
        return session('csp_nonce');
    }
    
    public static function nonceAttribute(): string
    {
        return 'nonce="' . self::nonce() . '"';
    }
    
    public static function isEnabled(): bool
    {
        return config('security.csp.enabled', true);
    }
    
    public static function isReportOnly(): bool
    {
        return config('security.csp.report_only', false);
    }
}
```

### 6. CSP Violation Reporting (`routes/web.php`)

Add this route to receive violation reports:

```php
// CSP violation reporting endpoint
Route::post('/csp-report', function (Request $request) {
    Log::warning('CSP Violation Report', [
        'report' => $request->all(),
        'user_agent' => $request->header('User-Agent'),
        'ip' => $request->ip(),
    ]);
    
    return response('', 204); // No content response
});
```

## How CSP Works

### 1. **Request Flow**
```
User visits page → Middleware adds CSP header → Browser receives page + CSP rules
```

### 2. **Browser Enforcement**
```
Browser tries to load resource → Checks against CSP rules → Allow or Block
```

### 3. **Report-Only Mode**
```
Browser finds violation → Logs to console → Sends report to your server → Doesn't block
```

### 4. **Enforcing Mode**
```
Browser finds violation → Blocks the resource → Sends report → Shows error in console
```

## Expected Output

### 1. **In Browser Developer Tools (Console)**

**With CSP Working:**
```
✅ No CSP violation errors
✅ Page loads correctly
✅ All JavaScript/CSS functions properly
```

**With CSP Violations:**
```
❌ Content-Security-Policy: The page's settings blocked a script...
❌ Content-Security-Policy: The page's settings blocked a style...
```

### 2. **HTTP Response Headers**

```http
Content-Security-Policy-Report-Only: default-src 'self' data: blob:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net http://localhost:5173 blob:; style-src 'self' 'unsafe-inline' https://fonts.bunny.net blob: data:; report-uri /csp-report
```

### 3. **Laravel Logs (Violation Reports)**

```
[2025-06-30 10:15:30] local.WARNING: CSP Violation Report {"report":{"csp-report":{"blocked-uri":"http://evil-site.com/malicious.js"}},"user_agent":"Mozilla/5.0...","ip":"127.0.0.1"}
```

## Development Workflow

### 1. **Initial Setup**
```bash
# Start with report-only mode
CSP_ENABLED=true
CSP_REPORT_ONLY=true
```

### 2. **Test Your Application**
```bash
npm run dev              # Start Vite
php artisan serve        # Start Laravel
```

### 3. **Check for Violations**
- Open browser developer tools (F12)
- Look for CSP violation messages
- Check Laravel logs for violation reports

### 4. **Fix Violations**
- Add legitimate sources to `config/security.php`
- Test again until no violations

### 5. **Enable Enforcement**
```bash
# Switch to enforcing mode
CSP_REPORT_ONLY=false
```

## Common CSP Rules Explained

| Directive | What It Controls | Example Values |
|-----------|------------------|----------------|
| `default-src` | Fallback for all resources | `'self'`, `data:` |
| `script-src` | JavaScript execution | `'self'`, `'unsafe-inline'`, CDNs |
| `style-src` | CSS stylesheets | `'self'`, `'unsafe-inline'`, font sites |
| `img-src` | Images | `'self'`, `data:`, `https:` |
| `connect-src` | AJAX/WebSocket | `'self'`, WebSocket URLs |
| `font-src` | Web fonts | `'self'`, font provider URLs |

## Quick Debugging

### If Your Website Breaks:
1. **Set `CSP_REPORT_ONLY=true`** in `.env`
2. **Check browser console** for violation messages
3. **Add missing sources** to `config/security.php`
4. **Test again** until clean

### Common Fixes:
- **Vite not loading**: Add `http://localhost:5173` to `script-src` and `connect-src`
- **Styles broken**: Add font providers to `style-src`
- **JavaScript errors**: Ensure `'unsafe-inline'` is in `script-src` (for Livewire/Alpine.js)

This implementation provides strong security while maintaining compatibility with Laravel 12, Vite, Livewire, and Alpine.js.
