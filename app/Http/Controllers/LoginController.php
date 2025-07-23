<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login pengguna
     */
    public function login(Request $request)
    {
        // Validasi form login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Coba autentikasi pengguna
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ]);
        }

        $user = Auth::user();

        // Redirect berdasarkan role pengguna
        return $this->redirectBerdasarkanRole($user);
    }

    /**
     * Menangani logout pengguna
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Redirect pengguna berdasarkan role mereka
     */
    private function redirectBerdasarkanRole($user)
    {
        $roleRoutes = [
            'superadmin' => 'superadmin.dashboard',
            'admin' => 'dashboard.admin',
            'teknisi' => 'teknisi.dashboard',
        ];

        $userRole = $user->role->role;

        if (isset($roleRoutes[$userRole])) {
            return redirect()->route($roleRoutes[$userRole]);
        }

        // Default redirect untuk pengguna biasa
        return redirect()->intended('/dashboard');
    }
}