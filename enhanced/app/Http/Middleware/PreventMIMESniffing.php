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

        // Add the nosniff header to the response
        // This prevents browsers from MIME-sniffing a response away from the declared content-type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        return $response;
    }
}
