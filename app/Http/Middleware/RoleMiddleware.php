<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Kalo belum login, atau role-nya nggak sesuai (misal petugas nyoba akses admin)
        if (!auth()->check() || auth()->user()->role !== $role) {
            // Tendang balik ke dashboard (bisa diganti abort(403) kalo mau)
            return redirect('/')->with('error', 'Akses Ditolak! Anda bukan ' . $role);
        }

        return $next($request);
    }
}
