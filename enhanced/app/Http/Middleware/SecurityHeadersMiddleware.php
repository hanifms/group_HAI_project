<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security Headers Middleware
 *
 * This middleware adds comprehensive security headers including CSP,
 * X-Frame-Options, X-Content-Type-Options, and other security headers
 * to protect against various web vulnerabilities.
 */
class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add Content Security Policy
        $this->addContentSecurityPolicy($response);

        // Add other security headers
        $this->addSecurityHeaders($response);

        return $response;
    }

    /**
     * Add Content Security Policy headers
     */
    private function addContentSecurityPolicy(Response $response): void
    {
        $csp = config('security.csp', []);

        if (empty($csp) || !config('security.csp.enabled', true)) {
            return;
        }

        // Build CSP header value
        $cspHeader = $this->buildCspHeader($csp);

        if (!empty($cspHeader)) {
            if (config('security.csp.report_only', false)) {
                $response->headers->set('Content-Security-Policy-Report-Only', $cspHeader);
            } else {
                $response->headers->set('Content-Security-Policy', $cspHeader);
            }
        }
    }

    /**
     * Add additional security headers
     */
    private function addSecurityHeaders(Response $response): void
    {
        $headers = config('security.headers', []);

        // X-Frame-Options
        if (isset($headers['x_frame_options'])) {
            $response->headers->set('X-Frame-Options', $headers['x_frame_options']);
        }

        // X-Content-Type-Options
        if (isset($headers['x_content_type_options'])) {
            $response->headers->set('X-Content-Type-Options', $headers['x_content_type_options']);
        }

        // X-XSS-Protection
        if (isset($headers['x_xss_protection'])) {
            $response->headers->set('X-XSS-Protection', $headers['x_xss_protection']);
        }

        // Referrer-Policy
        if (isset($headers['referrer_policy'])) {
            $response->headers->set('Referrer-Policy', $headers['referrer_policy']);
        }

        // Permissions-Policy
        if (isset($headers['permissions_policy'])) {
            $response->headers->set('Permissions-Policy', $headers['permissions_policy']);
        }

        // Strict-Transport-Security (HSTS)
        $this->addHstsHeader($response, $headers);
    }

    /**
     * Add HSTS header if enabled and conditions are met
     */
    private function addHstsHeader(Response $response, array $headers): void
    {
        $hsts = $headers['strict_transport_security'] ?? [];

        if (!($hsts['enabled'] ?? false) || !request()->isSecure()) {
            return;
        }

        $hstsValue = 'max-age=' . ($hsts['max_age'] ?? 31536000);

        if ($hsts['include_subdomains'] ?? true) {
            $hstsValue .= '; includeSubDomains';
        }

        if ($hsts['preload'] ?? false) {
            $hstsValue .= '; preload';
        }

        $response->headers->set('Strict-Transport-Security', $hstsValue);
    }

    /**
     * Build CSP header string from configuration
     */
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

        // Add report-to if configured
        if (!empty($csp['report_to'])) {
            $directives[] = 'report-to ' . $csp['report_to'];
        }

        return implode('; ', $directives);
    }
}
