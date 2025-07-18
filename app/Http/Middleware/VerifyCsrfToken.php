<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/create-transaction',  // Tambahkan URL route yang tidak perlu verifikasi CSRF
        'midtrans/callback',
         'api/*', 
    ];
    
}

