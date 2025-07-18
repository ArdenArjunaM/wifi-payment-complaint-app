<?php

namespace App\Http\Controllers;

use App\Models\StatusTagihan;
use Illuminate\Http\Request;

class StatusTagihanController extends Controller
{
    /**
     * Menyimpan status tagihan baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|string|max:255',
        ]);

        // Menyimpan status tagihan baru ke dalam database
        StatusTagihan::create([
            'status' => $request->status,
        ]);

        return redirect()->route('status-tagihan.index')->with('success', 'Status Tagihan berhasil dibuat.');
    }
}
