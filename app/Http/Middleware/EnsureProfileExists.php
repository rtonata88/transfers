<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileExists
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->hasProfile()) {
            // Allow access to profile creation route
            if (!$request->routeIs('profile.create', 'profile.store', 'profile.eligibility')) {
                return redirect()->route('profile.create');
            }
        }

        return $next($request);
    }
}
