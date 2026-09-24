<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            if ($request->user() && $request->user()->isTenant()) {
                return redirect()->route('tenant.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki hak akses Super Admin.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
