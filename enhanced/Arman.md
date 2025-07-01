# Security Enhancements Report for Website Based on ZAP Scan

## Overview
This report outlines the security enhancements made to the website based on the findings from the ZAP (OWASP Zed Attack Proxy) scan. The tasks include improving security in four key areas: redirect handling, cookie security, cross-domain JavaScript inclusion, and reviewing informational findings from the ZAP scan. Below are the specific tasks, files edited, and methods used for testing.

## Task 1: Missing Anti-clickjacking Header

### Issue:
- ZAP reported that the application was missing the `X-Frame-Options` header.
- This vulnerability can expose the application to clickjacking attacks, where malicious websites embed the app in a hidden iframe to trick users into performing unintended actions.

### Actions Taken:
- Created a custom middleware called `PreventClickjacking` to add the `X-Frame-Options: SAMEORIGIN` header to all HTTP responses.
- Registered the middleware globally by pushing it into the `web` middleware group via `AppServiceProvider`.

### Files Edited:
- `app/Http/Middleware/PreventClickjacking.php` – defines the middleware to apply the header.
- `app/Providers/AppServiceProvider.php` – registers the middleware globally.

### Testing Method:
- Launched the application locally and accessed multiple pages.
- Opened browser developer tools (F12), navigated to the Network tab, and inspected the HTTP response headers for each request.
- Verified that the X-Frame-Options: SAMEORIGIN header was present.

### Result:
- The X-Frame-Options: SAMEORIGIN header is now correctly applied to all pages.
- The application is protected from clickjacking attacks by preventing it from being embedded in external websites.

### Code Snippets:
- PreventClickjacking.php
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventClickjacking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the response after the request is handled
        $response = $next($request);

        // Set the X-Frame-Options header to SAMEORIGIN to prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); 
        
        // Continue with the response
        return $response;
    }
}
```

- AppServiceProvidor.php 
```
public function boot(): void
{
    // Register the middleware globally
    $this->app['router']->pushMiddlewareToGroup('web', PreventClickjacking::class);
}
```

## Task 2: X-Content-Type-Options Header Missing

### Issue:
- The application was missing the `X-Content-Type-Options: nosniff` header, which could allow browsers to MIME-sniff the content type and execute files in unexpected ways. This can lead to security vulnerabilities, such as cross-site scripting (XSS) or the execution of malicious files.

### Actions Taken:
- Implemented a custom middleware `PreventMIMESniffing` to add the `X-Content-Type-Options: nosniff` header to all HTTP responses.
- Registered this middleware globally for all `web` routes through the `AppServiceProvider`.
- Replaced the static file `/public/robots.txt` with a Laravel route to ensure the middleware could apply and inject the proper headers.

### Files Edited:
- `app/Http/Middleware/PreventMIMESniffing.php` – created middleware to add the header.
- `app/Providers/AppServiceProvider.php` – registered middleware globally.
- `routes/web.php` – added route to serve `robots.txt` dynamically through Laravel.

### Testing Method:
- Opened DevTools in the browser, navigated to http://127.0.0.1:8000/robots.txt, and inspected the response headers under the Network tab.
- Verified that the header X-Content-Type-Options: nosniff was included in the response.
- Confirmed the file is served via Laravel and not from /public, ensuring middleware is applied.

### Result:
- The application now includes X-Content-Type-Options: nosniff in all HTTP responses, preventing MIME-sniffing and reducing the risk of content-type-based attacks.

### Code Snippets:
- PreventMIMESniffing.php
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventMIMESniffing
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add the nosniff header
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        return $response;
    }
}
```

- AppServiceProvider.php
```
use App\Http\Middleware\PreventMIMESniffing;

public function boot(): void
{
    $this->app['router']->pushMiddlewareToGroup('web', PreventMIMESniffing::class);
}
```

- web.php
```
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow:", 200)
        ->header('Content-Type', 'text/plain');
});
```

## Task 3: X-Content-Type-Options Header Missing

### Issue:
- The web server was exposing its underlying technology stack via the `X-Powered-By` HTTP response header (e.g., `X-Powered-By: PHP/8.x`). This information could help attackers fingerprint the system and target known vulnerabilities specific to that technology.

### Actions Taken:
- Implemented a custom middleware `RemoveXPoweredByHeader` to remove the `X-Powered-By` header from all HTTP responses.
- Registered this middleware globally for all `web` routes using `AppServiceProvider`which ensures all application responses are filtered.

### Files Edited:
- `app/Http/Middleware/RemoveXPoweredByHeader.php` – create middleware that strips the `X-Powered-By` header.
- `app/Providers/AppServiceProvider.php` – registers the middleware globally to the `web` middleware group.
- `routes/web.php` – Created a Laravel-handled `/sitemap.xml` route to verify middleware works.

### Testing Method:
- Open browser Developer Tools → Network tab.
- Access: `http://127.0.0.1:8000/sitemap.xml`.
- Confirm that:
    - The response is served by Laravel (not from `/public`).
    - The `X-Powered-By` header is not present in the HTTP response.

### Result:
- The `X-Powered-By` header is now removed from all Laravel-handled responses, reducing server fingerprinting and improving information hygiene.

### Code Snippets:
- RemoveXPoweredByHeader.php
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveXPoweredByHeader
{

    public function handle(Request $request, Closure $next): Response
    {
        //handles any X-Powered-By headers added by PHP or the server before Laravel kicks in
        header_remove("X-Powered-By");

        $response = $next($request);

        // Remove the X-Powered-By header
        $response->headers->remove('X-Powered-By');
        
        return $response;
    }
}
```

- AppServiceProvider.php
```
use App\Http\Middleware\RemoveXPoweredByHeader;

public function boot(): void
{
    $this->app['router']->pushMiddlewareToGroup('web', RemoveXPoweredByHeader::class);
}
```

- web.php
```
Route::get('/sitemap.xml', function () {
    return response('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>', 200)
        ->header('Content-Type', 'application/xml');
});
```
