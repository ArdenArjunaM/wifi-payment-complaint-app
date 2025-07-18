<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Menangani form registrasi
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:10|unique:users',
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15|unique:users,no_hp',
        ]);

        // Jika gagal validasi
        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Buat user baru
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status' => 'Belum Berlangganan',
            'role' => 'user',
        ]);

        // Trigger event email verifikasi
        event(new Registered($user));

        // Login otomatis
        Auth::login($user);

        // Redirect ke halaman notifikasi verifikasi email (WAJIB: route ini harus ada di web.php)
        return redirect()->route('verification.notice');
    }
}
