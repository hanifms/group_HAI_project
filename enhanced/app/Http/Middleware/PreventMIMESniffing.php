<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventMIMESniffing
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

        // Set the X-Content-Type-Options header to nosniff to prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Return the response
        return $response;
    }
}
