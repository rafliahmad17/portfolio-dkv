<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Memverifikasi apakah user yang sedang login memiliki role
     * yang sesuai dengan parameter yang diberikan di routes.
     *
     * Contoh penggunaan di routes: middleware('role:admin,guru')
     *
     * @param  array<string> ...$roles  Role yang diizinkan mengakses rute ini
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengunjungi halaman ini.');
        }

        return $next($request);
    }
}