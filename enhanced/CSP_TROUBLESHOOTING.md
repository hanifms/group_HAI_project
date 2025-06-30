# CSP Troubleshooting Guide for Travel Booking Application

## Quick Fix for UI Issues

If your UI is not functioning after CSP implementation, follow these steps:

### 1. Immediate Fix (Development)

Add these lines to your `.env` file:

```bash
CSP_ENABLED=true
CSP_REPORT_ONLY=true
CSP_REPORT_URI=/csp-report
```

This enables CSP in "report-only" mode, which will report violations but not block them, and sets up violation reporting.

### 2. Test the Configuration

Visit: `http://localhost:8000/csp-test.html` to test your CSP configuration.

### 3. Run the CSP Test Script

```bash
php test-csp.php
```

## IPv6 CSP Issue (FIXED)

**Previous Issue:**
Browsers were showing CSP parsing errors for IPv6 addresses like `http://[::1]:5173`.

**Root Cause:**
The CSP specification doesn't support IPv6 addresses in square brackets (`[::1]`) as host sources.

**Solution Applied:**
- Removed all `[::1]` entries from CSP configuration
- Kept `localhost` and `127.0.0.1` for Vite development
- Added proper CSP reporting endpoint

**What was removed:**
- `http://[::1]:5173` (Vite dev server)
- `http://[::1]:8000` (Laravel dev server)  
- `ws://[::1]:5173` (Vite WebSocket)
- `ws://[::1]:*` (Generic IPv6 WebSocket)

**What remains for Vite support:**
- `http://localhost:5173`
- `http://127.0.0.1:5173`
- `ws://localhost:5173`
- `ws://127.0.0.1:5173`

## Common Issues and Solutions

### Issue 1: JavaScript Not Working

**Symptoms:**
- Dropdowns don't work
- Forms don't submit
- Interactive elements are broken
- Browser console shows CSP violations

**Solution:**
Ensure these are in your `script-src` directive in `config/security.php`:
```php
'script-src' => [
    "'self'",
    "'unsafe-inline'",  // Required for inline scripts
    "'unsafe-eval'",    // Required for Livewire and some frameworks
    // ... other sources
],
```

### Issue 2: Vite Development Server Issues

**Symptoms:**
- Hot reload not working
- CSS/JS changes not reflected
- Console errors about localhost:5173

**Solution:**
1. Make sure Vite is running: `npm run dev`
2. Check that these are in your CSP directives:
```php
'script-src' => [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://[::1]:5173',  // IPv6 localhost
    'ws://localhost:5173',
    'ws://127.0.0.1:5173',
    'ws://[::1]:5173',    // IPv6 WebSocket
    // ...
],
'connect-src' => [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://[::1]:5173',  // IPv6 localhost
    'ws://localhost:5173',
    'ws://127.0.0.1:5173',
    'ws://[::1]:5173',    // IPv6 WebSocket
    // ...
],
```

### Issue 3: Livewire Components Not Working

**Symptoms:**
- Form submissions fail
- Real-time updates don't work
- AJAX requests are blocked

**Solution:**
Ensure these are properly configured:
```php
'script-src' => [
    "'unsafe-inline'",
    "'unsafe-eval'",
],
'connect-src' => [
    "'self'",
    // Allow AJAX requests to your domain
],
```

### Issue 4: Alpine.js Not Working

**Symptoms:**
- `x-data`, `x-show`, `x-if` directives not working
- Modal dialogs don't open/close
- Dropdown menus not responding

**Solution:**
Alpine.js requires inline script execution:
```php
'script-src' => [
    "'unsafe-inline'",
],
```

### Issue 5: Styles Not Applied

**Symptoms:**
- Page looks unstyled
- Tailwind CSS not working
- Inline styles not applied

**Solution:**
```php
'style-src' => [
    "'self'",
    "'unsafe-inline'",  // Required for inline styles
    'https://fonts.bunny.net',  // For web fonts
],
```

### Issue 6: IPv6 Localhost Issues (Vite on [::1]:5173)

**Symptoms:**
- CSP violations showing `http://[::1]:5173`
- Vite assets blocked despite localhost being allowed
- Browser using IPv6 instead of IPv4 localhost

**Solution:**
Ensure IPv6 localhost addresses are included:
```php
'script-src' => [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://[::1]:5173',        // IPv6 localhost
],
'script-src-elem' => [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://[::1]:5173',        // IPv6 localhost
],
'style-src-elem' => [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://[::1]:5173',        // IPv6 localhost
],
'connect-src' => [
    'ws://localhost:5173',
    'ws://127.0.0.1:5173',
    'ws://[::1]:5173',          // IPv6 WebSocket
],
```

## Environment-Specific Settings

### Development (.env)
```bash
CSP_ENABLED=true
CSP_REPORT_ONLY=true
HSTS_ENABLED=false
```

### Production (.env)
```bash
CSP_ENABLED=true
CSP_REPORT_ONLY=false
HSTS_ENABLED=true  # Only if using HTTPS
```

## Testing Your CSP

### 1. Browser Console
Open browser developer tools (F12) and check the Console tab for CSP violation messages.

### 2. Test Page
Visit `http://localhost:8000/csp-test.html` to run automated tests.

### 3. CSP Test Script
```bash
php test-csp.php
```

## Understanding CSP Directives

| Directive | Purpose | Common Values |
|-----------|---------|---------------|
| `default-src` | Fallback for other directives | `'self'` |
| `script-src` | JavaScript execution | `'self'`, `'unsafe-inline'`, `'unsafe-eval'` |
| `style-src` | CSS stylesheets | `'self'`, `'unsafe-inline'` |
| `img-src` | Images | `'self'`, `data:`, `https:` |
| `connect-src` | AJAX, WebSocket | `'self'`, `ws:`, `wss:` |
| `font-src` | Web fonts | `'self'`, `https://fonts.bunny.net` |

## Framework-Specific Requirements

### Laravel Jetstream + Livewire
```php
'script-src' => [
    "'self'",
    "'unsafe-inline'",  // Required
    "'unsafe-eval'",    // Required
],
```

### Vite (Development)
```php
'script-src' => [
    'http://localhost:5173',
],
'connect-src' => [
    'ws://localhost:5173',
],
```

### Alpine.js
```php
'script-src' => [
    "'unsafe-inline'",  // Required
],
```

### Tailwind CSS
```php
'style-src' => [
    "'unsafe-inline'",  // Required for utility classes
],
```

## Gradual CSP Implementation

1. **Start with Report-Only**: `CSP_REPORT_ONLY=true`
2. **Monitor Violations**: Check browser console
3. **Fix Issues**: Update CSP directives as needed
4. **Test Thoroughly**: Use test page and script
5. **Enable Enforcement**: `CSP_REPORT_ONLY=false`

## Security vs Functionality Balance

### More Secure (Restrictive)
- Remove `'unsafe-inline'` and `'unsafe-eval'`
- Use nonces for inline scripts
- Whitelist specific domains only

### More Functional (Permissive)
- Allow `'unsafe-inline'` and `'unsafe-eval'`
- Use broader source allowlists
- Enable data: and blob: URLs

For this Laravel application, we've chosen a balanced approach that maintains security while ensuring all framework features work correctly.

## Getting Help

1. Check browser console for specific violation messages
2. Run the CSP test tools provided
3. Review the configuration in `config/security.php`
4. Test in report-only mode first

## Files to Check

- `config/security.php` - Main CSP configuration
- `.env` - Environment variables
- `test-csp.php` - Testing script
- `public/csp-test.html` - Browser-based tests
