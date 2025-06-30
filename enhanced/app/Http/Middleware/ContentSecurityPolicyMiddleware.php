<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Content Security Policy Middleware
 *
 * This middleware adds Content Security Policy headers to protect against
 * XSS attacks, clickjacking, and other code injection attacks.
 */
class ContentSecurityPolicyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
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
            // Add CSP header
            $response->headers->set('Content-Security-Policy', $cspHeader);

            // Add CSP Report-Only header if enabled
            if (config('security.csp.report_only', false)) {
                $response->headers->set('Content-Security-Policy-Report-Only', $cspHeader);
            }
        }

        return $response;
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

        return implode('; ', $directives);
    }
}
