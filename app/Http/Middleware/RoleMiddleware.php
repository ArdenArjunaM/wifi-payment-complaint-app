<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan pengguna login dan memiliki role yang sesuai
        if (Auth::check() && in_array(Auth::user()->role->role, $roles)) {
            return $next($request);  // Melanjutkan permintaan jika role cocok
        }

        // Jika tidak sesuai role, bisa diarahkan ke halaman login atau halaman lain sesuai kebutuhan
        return redirect()->route('login');  // Atau ke halaman lain sesuai kebutuhan
    }
}
