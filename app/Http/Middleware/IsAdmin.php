<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
    }
}