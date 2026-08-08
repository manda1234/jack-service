<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAnyGuardAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check() && !Auth::guard('customer')->check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}