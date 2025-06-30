# Security Enhancements Report for Website Based on ZAP Scan

## Overview
This report outlines the security enhancements made to the website based on the findings from the ZAP (OWASP Zed Attack Proxy) scan. The tasks include improving security in four key areas: redirect handling, cookie security, cross-domain JavaScript inclusion, and reviewing informational findings from the ZAP scan. Below are the specific tasks, files edited, and methods used for testing.

## Task 1: Redirect Handling
### Issue:
- ZAP flagged an issue where redirects might expose sensitive data or allow open redirect vulnerabilities. This typically happens when a user is redirected without validating the destination URL, especially after login or other authentication flows.

### Actions Taken:
- Created a custom response for login to ensure all redirection targets are trusted.
- Restricted post-login redirects to internal, validated URLs only.
- Introduced a fallback redirect to /dashboard if the intended redirect URL is not available or unsafe.

### Files Edited:
- `app/Http/Controllers/CustomLoginResponse.php` – created a custom response class to handle redirects after a successful login
-`app/Providers/FortifyServiceProvider.php` - Bound LoginResponse to CustomLoginResponse to customize the login response behavior and manage the redirect after a successful login.

### Testing Method:
- Manually tested login redirects with manipulated intended parameters.
- Verified that all redirects stay within the application domain.
- Confirmed fallback behavior using invalid or missing redirect URLs.

### Result:
- The redirect logic now ensures:
- Redirects only point to internal pages.
- No sensitive information leakage via redirect URLs.
- A secure, consistent user experience post-login.

### Code Snippet
- CustomLoginResponse.php
```
<?php

namespace App\Http\Controllers;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $intendedUrl = session()->pull('url.intended', '/dashboard');

        // Validate that the redirect URL is internal
        if (!str_starts_with($intendedUrl, '/')) {
            $intendedUrl = '/dashboard';
        }

        return redirect()->intended($intendedUrl);
    }
}
```

- FortifyServiceProvider.php
```
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Controllers\CustomLoginResponse;

public function boot(): void
{
    // other Fortify bindings...

    // Bind custom login response handler
    $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);
}
```
---

## Task 2: Cookie Security
### Issue:
- ZAP flagged that cookies set by the application did not include critical security attributes such as HttpOnly, Secure, or SameSite. This could potentially expose cookies to client-side JavaScript (leading to session hijacking) or allow cross-site request attacks.

### Actions Taken:
- Enabled the HttpOnly flag to prevent JavaScript access to cookies.
- Ensured the Secure flag is set so cookies are only sent via HTTPS.
- Applied the SameSite attribute to protect against CSRF attacks.

### Files Edited:
- `config/session.php` – updated cookie settings to enforce security features (e.g., `Secure`, `HttpOnly`, `SameSite`).

### Testing Method:
- Opened Developer Tools (F12) in browser → Application → Cookies
- Verified the presence of HttpOnly, Secure, and SameSite flags in session cookies.

### Result:
- The application now securely manages cookies:
- JavaScript can’t access them (HttpOnly)
- They’re only transmitted over HTTPS (Secure)
- Cross-site attacks are mitigated (SameSite)
  
### Code Snippet
config/session.php
```
return [

    // ...

    'secure' => env('SESSION_SECURE_COOKIE', true), // Ensures HTTPS only

    'http_only' => true, // Prevent JavaScript access

    'same_site' => 'lax', // Mitigates CSRF

];
```

---

## Task 3: Cross-Domain JavaScript Inclusion
### Issue:
- ZAP flagged a potential issue with JavaScript source inclusion—specifically referencing a request to /sitemap.xml. Although the endpoint returned a 404 Not Found, the concern was that scripts or XML references could potentially allow cross-domain injection if not controlled properly.

### Actions Taken:
- Verified that http://127.0.0.1:8000/sitemap.xml does not exist and is not served by Laravel, confirming there is no real attack surface.
- Reviewed all <script> tags and external asset loading in Blade templates and ensured all JavaScript files were sourced from trusted CDNs or internal paths. 
- Leveraged the existing Content Security Policy (CSP) configuration to strictly control where scripts can be loaded from.
- Confirmed that CSP blocks untrusted scripts through a whitelist of domains in config/security.php.


### Files Edited:
- `.env` – added CSP-related settings.
- `resources/views/layouts/app.blade.php` – updated meta tags to include the CSP headers.

### Testing Method:
- Used browser developer tools to inspect the response headers and ensure the CSP header was correctly applied.
- Attempted to include external scripts from untrusted domains and confirmed they were blocked.

### Result:
- No actual vulnerability found — /sitemap.xml not served by Laravel.
- All JavaScript inclusions are from self or trusted CDNs.
- CSP configuration prevents any unauthorized or cross-domain script loading.
---

## Task 4: Informational Findings Review
### Issue:
- ZAP flagged several informational alerts during the scan. These are not direct vulnerabilities but require developer awareness to ensure best practices in authentication and session management are followed.


### Informational Flags Identified:
1. **Authentication Request Identified** – `POST http://127.0.0.1:8000/login`
2. **Modern Web Application Reference** – `http://127.0.0.1:5000/`
3. **Session Management Response Identified** – `http://127.0.0.1:8000/`

---

### Actions Taken:

#### 1. Authentication Request Identified
- Reviewed the `/login` endpoint, which is flagged because it handles user credentials.
- Ensured CSRF protection is enabled via Laravel middleware.
- Confirmed that the login route uses the `POST` method, not `GET`.
- Verified login is rate-limited using Fortify's built-in rate limiter.

#### 2. Modern Web Application Reference
- Checked port `5000` on localhost. No application was running on this port.
- Concluded this flag is informational and likely a **false positive**, commonly caused by ZAP probing common dev ports (like Flask or Webpack dev servers).
- Ensured that no sensitive services are publicly accessible on this port.

#### 3. Session Management Response Identified
- Reviewed Laravel’s session configuration in `config/session.php`.
- Confirmed:
  - `HttpOnly` is set to `true`.
  - `Secure` cookie flag is enforced in `session.php` via `'secure' => env('SESSION_SECURE_COOKIE', true),`.
  - `SameSite` is set to `'lax'` to prevent CSRF.
  - Laravel **automatically regenerates session IDs** after successful login to prevent session fixation.

---

### Files Reviewed:
- `routes/web.php` – verified login route.
- `app/Providers/FortifyServiceProvider.php` – checked rate limiting and authentication logic.
- `config/session.php` – confirmed session security configuration.


---

### Testing Methods:

| Finding | Testing Performed |
|--------|-------------------|
| **Authentication Request (/login)** | - Used DevTools → Network tab → Inspected the `POST /login` request.<br>- Verified `X-CSRF-TOKEN` header is included.<br>- Confirmed the method is POST.<br>- Checked rate limiting by submitting multiple failed attempts. |
| **Modern Web App Reference (:5000)** | - Visited `http://127.0.0.1:5000/` manually.<br>- Confirmed no service was running.<br>- Checked server logs to verify no interaction. |
| **Session Management (/)** | - Inspected cookies in DevTools → Application tab.<br>- Verified `HttpOnly`, `Secure`, and `SameSite=Lax` attributes.<br>- Logged in and observed that session ID was regenerated using Laravel’s behavior. |

---

### Result:
- **Authentication flow** is secure with CSRF protection, POST-only submission, and rate limiting.
- **Port 5000 flag** was a false positive; no application is exposed on that port.
- **Session management** follows best practices using secure cookies and automatic session regeneration.
- No changes were required, but this task confirmed that Laravel’s default security settings align with best practices.


## Conclusion
The website's security has been significantly improved by addressing the issues identified in the ZAP scan. The steps taken have enhanced the security of the website in areas such as redirect handling, cookie security, cross-domain JavaScript inclusion, and addressed relevant informational findings. This ensures a safer and more secure browsing experience for users.

