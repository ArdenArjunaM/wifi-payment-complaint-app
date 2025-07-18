<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    // =============================
    // LOGIN MANAGEMENT
    // =============================

    /**
     * Menampilkan form login
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi pengguna
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect berdasarkan role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    /**
     * Redirect pengguna berdasarkan role
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->role->role ?? 'user') {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard');
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'user':
                return redirect()->route('user.dashboard');
            case 'teknisi':
                return redirect()->route('teknisi.dashboard');
            default:
                return redirect()->route('login')->with('error', 'Role tidak dikenali.');
        }
    }

    // =============================
    // REGISTRASI MANAGEMENT
    // =============================

    /**
     * Menampilkan form registrasi
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 2, // Role user default
        ]);

        // Trigger email verification
        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    // =============================
    // EMAIL VERIFICATION MANAGEMENT
    // =============================

    /**
     * Menampilkan halaman notice verifikasi email
     */
    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    /**
     * Proses verifikasi email
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Email sudah diverifikasi.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi.');
    }

    /**
     * Mengirim ulang email verifikasi
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi baru telah dikirim ke email Anda.');
    }

    /**
     * Mengubah email pengguna
     */
    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $user = $request->user();
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Email berhasil diubah. Silakan verifikasi email baru Anda.');
    }

    // =============================
    // DASHBOARD BERDASARKAN ROLE
    // =============================

    /**
     * Dashboard untuk superadmin
     */
    public function superadminDashboard()
    {
        return view('superadmin.dashboard');
    }

    /**
     * Dashboard untuk admin
     */
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    /**
     * Dashboard untuk user
     */
    public function userDashboard()
    {
        return view('user.dashboard');
    }

    /**
     * Dashboard untuk teknisi
     */
    public function teknisiDashboard()
    {
        return view('teknisi.dashboard');
    }
}