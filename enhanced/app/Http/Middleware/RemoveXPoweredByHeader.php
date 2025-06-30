<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveXPoweredByHeader
{

    public function handle(Request $request, Closure $next): Response
    {
        header_remove("X-Powered-By");

        $response = $next($request);

        // Remove the X-Powered-By header
        $response->headers->remove('X-Powered-By');
        
        return $response;
    }
}
