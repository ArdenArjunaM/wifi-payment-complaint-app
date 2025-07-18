<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Menampilkan halaman notice verifikasi email
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Memproses verifikasi email
     */
    public function verify(EmailVerificationRequest $request)
    {
        // Cek apakah email sudah terverifikasi
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Email sudah diverifikasi!');
        }

        // Tandai email sebagai terverifikasi
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi!');
    }

    /**
     * Mengirim ulang notifikasi verifikasi email
     */
    public function resend(Request $request)
    {
        // Cek apakah email sudah terverifikasi
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // Kirim ulang notifikasi verifikasi
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ulang!');
    }
}