<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika permintaan mengharapkan response JSON, jangan arahkan kemana-mana
        if ($request->expectsJson()) {
            return null;
        }

        // Jika tidak dalam permintaan JSON, arahkan pengguna ke halaman login
        return route('login');  // pastikan route 'login' ada di routes/web.php
    }
}
