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
