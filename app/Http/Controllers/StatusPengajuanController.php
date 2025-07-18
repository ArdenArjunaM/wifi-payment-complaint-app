<?php

namespace App\Http\Controllers;

use App\Models\StatusPengajuan;
use Illuminate\Http\Request;

class StatusPengajuanController extends Controller
{
    /**
     * Display a listing of the resource (Daftar Status Pengajuan).
     */
    public function index()
    {
        // Mengambil semua status pengajuan menggunakan Eloquent
        $status_pengajuan = StatusPengajuan::all();

        // Mengirim data status pengajuan ke view
        return view('admin.status_pengajuan.index', compact('status_pengajuan'));
    }

    /**
     * Show the form for creating a new resource (Form untuk membuat status pengajuan).
     */
    public function create()
    {
        // Menampilkan form untuk menambahkan status pengajuan baru
        return view('admin.status_pengajuan.create');
    }

    /**
     * Store a newly created resource in storage (Menyimpan Status Pengajuan).
     */
    public function store(Request $request)
    {
        // Validasi input status pengajuan
        $validated = $request->validate([
            'status' => 'required|string|max:50|unique:status_pengajuan,status',
        ]);

        // Menyimpan status pengajuan baru menggunakan Eloquent ORM
        StatusPengajuan::create([
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('statuspengajuan.index')->with('success', 'Status pengajuan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource (Detail Status Pengajuan).
     */
    public function show(string $id)
    {
        // Mengambil detail status pengajuan berdasarkan ID menggunakan Eloquent ORM
        $status_pengajuan = StatusPengajuan::findOrFail($id);

        // Mengirim data status pengajuan ke view
        return view('admin.status_pengajuan.show', compact('status_pengajuan'));
    }

    /**
     * Show the form for editing the specified resource (Edit Status Pengajuan).
     */
    public function edit(string $id)
    {
        // Mengambil data status pengajuan yang akan diedit menggunakan Eloquent ORM
        $status_pengajuan = StatusPengajuan::findOrFail($id);

        // Mengirim data status pengajuan ke view
        return view('admin.status_pengajuan.edit', compact('status_pengajuan'));
    }

    /**
     * Update the specified resource in storage (Memperbarui Status Pengajuan).
     */
    public function update(Request $request, string $id)
    {
        // Validasi input status pengajuan
        $validated = $request->validate([
            'status' => 'required|string|max:50|unique:status_pengajuan,status,' . $id . ',id_status_pengajuan',
        ]);

        // Mengambil data status pengajuan yang akan diperbarui menggunakan Eloquent ORM
        $status_pengajuan = StatusPengajuan::findOrFail($id);

        // Memperbarui status pengajuan menggunakan Eloquent ORM
        $status_pengajuan->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('statuspengajuan.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (Menghapus Status Pengajuan).
     */
    public function destroy(string $id)
    {
        // Mengambil data status pengajuan yang akan dihapus menggunakan Eloquent ORM
        $status_pengajuan = StatusPengajuan::findOrFail($id);

        // Menghapus status pengajuan menggunakan Eloquent ORM
        $status_pengajuan->delete();

        return redirect()->route('statuspengajuan.index')->with('success', 'Status pengajuan berhasil dihapus.');
    }
}
