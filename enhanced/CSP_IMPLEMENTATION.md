# Content Security Policy (CSP) Implementation

This document explains the Content Security Policy implementation in the Travel Booking Application.

## Overview

Content Security Policy (CSP) is a security feature that helps prevent cross-site scripting (XSS) attacks, clickjacking, and other code injection attacks by controlling which resources the browser is allowed to load.

## Files Structure

```
app/
├── Http/
│   └── Middleware/
│       ├── ContentSecurityPolicyMiddleware.php  # CSP-only middleware
│       └── SecurityHeadersMiddleware.php        # Comprehensive security headers
├── Helpers/
│   └── CspHelper.php                           # CSP utility functions
config/
└── security.php                               # Security configuration
tests/
├── Feature/
│   └── Security/
│       └── ContentSecurityPolicyTest.php      # CSP integration tests
└── Unit/
    └── Helpers/
        └── CspHelperTest.php                   # CSP helper unit tests
```

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Enable/disable CSP headers
CSP_ENABLED=true

# Enable report-only mode (for testing CSP policies)
CSP_REPORT_ONLY=false

# Additional Security Headers
X_FRAME_OPTIONS=SAMEORIGIN
X_CONTENT_TYPE_OPTIONS=nosniff
X_XSS_PROTECTION="1; mode=block"
REFERRER_POLICY=strict-origin-when-cross-origin
PERMISSIONS_POLICY="camera=(), microphone=(), geolocation=()"

# HSTS (HTTP Strict Transport Security) - Only enable for HTTPS sites
HSTS_ENABLED=false
HSTS_MAX_AGE=31536000
HSTS_INCLUDE_SUBDOMAINS=true
HSTS_PRELOAD=false
```

### Security Configuration File

The `config/security.php` file contains comprehensive CSP directives:

- **default-src**: Fallback policy for other directives
- **script-src**: Controls JavaScript execution
- **style-src**: Controls CSS loading
- **img-src**: Controls image loading
- **font-src**: Controls font loading
- **connect-src**: Controls AJAX/WebSocket connections
- **frame-src**: Controls iframe embedding
- **object-src**: Controls plugins (set to 'none' for security)

## Middleware

### SecurityHeadersMiddleware

Applied globally to all web routes, this middleware adds:

- Content Security Policy headers
- X-Frame-Options (clickjacking protection)
- X-Content-Type-Options (MIME sniffing protection)
- X-XSS-Protection (legacy XSS protection)
- Referrer-Policy (referrer information control)
- Permissions-Policy (browser feature control)
- Strict-Transport-Security (HTTPS enforcement)

### ContentSecurityPolicyMiddleware

A standalone CSP-only middleware that can be applied to specific routes if needed.

## Usage

### Basic Usage

The CSP is automatically applied to all web routes. No additional configuration is required for basic protection.

### Blade Directives

Use these Blade directives in your templates:

```blade
{{-- Check if CSP is enabled --}}
@cspEnabled
    <p>CSP is active</p>
@endcspEnabled

{{-- Add nonce to inline scripts --}}
<script @cspNonce>
    // Your inline JavaScript here
    console.log('This script has a CSP nonce');
</script>

{{-- Get nonce value only --}}
<script nonce="@cspNonceValue">
    // Alternative syntax
</script>
```

### Helper Functions

Use the CSP helper in PHP code:

```php
use App\Helpers\CspHelper;

// Generate a nonce for inline scripts
$nonce = CspHelper::nonce();

// Get nonce attribute
$nonceAttr = CspHelper::nonceAttribute();

// Check if CSP is enabled
if (CspHelper::isEnabled()) {
    // CSP-specific logic
}

// Check allowed sources
if (CspHelper::isSourceAllowed('script-src', 'https://cdn.example.com')) {
    // Source is allowed
}
```

## CSP Directives Explained

### Script Sources (`script-src`)
- `'self'`: Allow scripts from same origin
- `'unsafe-inline'`: Allow inline scripts (needed for some frameworks)
- `'unsafe-eval'`: Allow eval() (needed for development tools)
- CDN domains: Allow popular CDNs for libraries

### Style Sources (`style-src`)
- `'self'`: Allow styles from same origin
- `'unsafe-inline'`: Allow inline styles (needed for CSS frameworks)
- `https://fonts.googleapis.com`: Allow Google Fonts
- CDN domains: Allow popular CDNs for CSS libraries

### Image Sources (`img-src`)
- `'self'`: Allow images from same origin
- `data:`: Allow data URLs (base64 images)
- `https:`: Allow any HTTPS image source
- `blob:`: Allow blob URLs (file uploads)

## Development vs Production

### Development Settings
- `'unsafe-inline'` and `'unsafe-eval'` allowed for scripts
- More permissive source lists
- Report-only mode for testing

### Production Settings
- Stricter policies
- Remove `'unsafe-inline'` and `'unsafe-eval'` where possible
- Enforcing mode (not report-only)
- Specific domain whitelisting

## Testing CSP

### Report-Only Mode

Enable report-only mode to test CSP without breaking functionality:

```env
CSP_REPORT_ONLY=true
```

### Running Tests

```bash
# Run CSP-specific tests
php artisan test tests/Feature/Security/ContentSecurityPolicyTest.php
php artisan test tests/Unit/Helpers/CspHelperTest.php

# Run all security tests
php artisan test --path=tests/Feature/Security
```

## Common Issues and Solutions

### Issue: Inline Scripts Blocked
**Solution**: Use nonces or move scripts to external files

```blade
{{-- Before (blocked) --}}
<script>
    alert('Hello');
</script>

{{-- After (allowed) --}}
<script @cspNonce>
    alert('Hello');
</script>
```

### Issue: Third-party Resources Blocked
**Solution**: Add domains to appropriate directives in `config/security.php`

```php
'script-src' => [
    "'self'",
    'https://your-cdn-domain.com',
],
```

### Issue: Development Tools Not Working
**Solution**: Use report-only mode during development

```env
CSP_REPORT_ONLY=true
```

## Security Benefits

1. **XSS Protection**: Prevents malicious script injection
2. **Clickjacking Protection**: Prevents UI redressing attacks
3. **Data Injection Protection**: Controls resource loading
4. **Mixed Content Protection**: Enforces HTTPS usage
5. **Monitoring**: Reports policy violations for analysis

## Best Practices

1. **Start with Report-Only**: Test policies before enforcing
2. **Use Nonces**: For unavoidable inline scripts
3. **Minimize Unsafe Directives**: Avoid `'unsafe-inline'` in production
4. **Regular Audits**: Review and update policies regularly
5. **Monitor Violations**: Set up violation reporting endpoints

## Monitoring and Reporting

To enable CSP violation reporting, set up an endpoint:

```env
CSP_REPORT_URI=https://your-app.com/csp-report
```

Create a route to handle violations:

```php
Route::post('/csp-report', function (Request $request) {
    // Log CSP violations
    Log::warning('CSP Violation', $request->all());
});
```

## Troubleshooting

### Check CSP Headers

Use browser developer tools to verify CSP headers are present:

1. Open Network tab
2. Reload page
3. Check response headers for `Content-Security-Policy`

### Validate CSP Policy

Use online CSP validators:
- [CSP Evaluator](https://csp-evaluator.withgoogle.com/)
- [Report URI CSP Analyser](https://report-uri.com/home/analyse)

### Debug Violations

Check browser console for CSP violation messages and adjust policies accordingly.

## Project-Specific Configuration

This Travel Booking Application uses specific technologies that require additional CSP configuration:

- **Laravel 12** with Jetstream
- **Vite** for asset bundling and hot reload
- **Livewire** for dynamic components
- **Tailwind CSS** for styling
- **Fonts.bunny.net** for web fonts

### Required Environment Variables

For development, add these to your `.env` file:

```env
# Enable CSP in report-only mode for development
CSP_ENABLED=true
CSP_REPORT_ONLY=true

# Disable HSTS for local development
HSTS_ENABLED=false
```

For production:

```env
# Enable CSP in enforcing mode
CSP_ENABLED=true
CSP_REPORT_ONLY=false

# Enable HSTS for HTTPS sites
HSTS_ENABLED=true
```

### Vite Development Server Support

The CSP configuration includes support for Vite development server:

- `http://localhost:5173` - Vite dev server
- `ws://localhost:5173` - Vite WebSocket for hot reload
- Alternative IP addresses for development flexibility

### Fonts Configuration

The project uses Bunny Fonts, which is included in the CSP:
- `https://fonts.bunny.net` added to `font-src` and `style-src`

## Troubleshooting UI Issues

### If UI is not working after CSP implementation:

1. **Check Browser Console**: Look for CSP violation errors
2. **Enable Report-Only Mode**: Set `CSP_REPORT_ONLY=true` in `.env`
3. **Verify Vite is Running**: Ensure `npm run dev` is active for development
4. **Check WebSocket Connections**: Vite hot reload needs WebSocket access

### Common Fixes:

1. **Scripts Not Loading**:
   ```env
   # Ensure report-only mode for development
   CSP_REPORT_ONLY=true
   ```

2. **Styles Not Applying**:
   - Verify Bunny Fonts are in font-src
   - Check if inline styles are needed for Livewire

3. **Livewire Not Working**:
   - Ensure WebSocket connections are allowed
   - Check if `'unsafe-inline'` is in script-src for Livewire

### Browser Console Debugging

Check for messages like:
```
Refused to load the script 'http://localhost:5173/@vite/client' because it violates the following Content Security Policy directive
```

If you see these, the CSP needs adjustment for your specific setup.
