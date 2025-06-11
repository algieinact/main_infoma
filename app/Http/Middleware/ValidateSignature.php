<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        // Basic stub: always allow. Replace with real signature validation logic.
        return $next($request);
    }
}
