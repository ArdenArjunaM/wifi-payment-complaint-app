<?php

namespace App\Http\Controllers;

use App\Models\PaketWifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaketWifiController extends Controller
{
    /**
     * Menampilkan daftar semua paket WiFi untuk Admin
     */
    public function index()
    {
        $paketWifi = PaketWifi::all();
        $totalPaket = $paketWifi->count();

        return view('dashboard.datapaket.index', compact('paketWifi', 'totalPaket'));
    }

    /**
     * Menampilkan daftar semua paket WiFi untuk SuperAdmin
     */
    public function datapaket()
    {
        $paketWifi = PaketWifi::all();
        $totalPaket = $paketWifi->count();

        return view('superadmin.datapaket.index', compact('paketWifi', 'totalPaket'));
    }

    /**
     * Menampilkan paket WiFi untuk User
     */
    public function showPaketWifi()
    {
        $paketWifi = PaketWifi::all();
        
        return view('user.paketwifi', compact('paketWifi'));
    }

    /**
     * Menampilkan form untuk menambahkan paket WiFi baru (Admin)
     */
    public function create()
    {
        return view('dashboard.datapaket.create');
    }

    /**
     * Menampilkan form untuk menambahkan paket WiFi baru (SuperAdmin)
     */
    public function createSuperAdmin()
    {
        return view('superadmin.datapaket.create');
    }

    /**
     * Menyimpan paket WiFi baru ke dalam database (Admin)
     */
    public function store(Request $request)
    {
        $this->validatePaketWifi($request);

        $gambarPath = $this->handleImageUpload($request);

        $this->createPaketWifi($request, $gambarPath);

        return redirect()->route('dashboard.datapaket.index')
            ->with('success', 'Paket WiFi berhasil ditambahkan.');
    }

    /**
     * Menyimpan paket WiFi baru ke dalam database (SuperAdmin)
     */
    public function storeSuperAdmin(Request $request)
    {
        $this->validatePaketWifi($request);

        $gambarPath = $this->handleImageUpload($request);

        $this->createPaketWifi($request, $gambarPath);

        return redirect()->route('superadmin.datapaket.index')
            ->with('success', 'Paket WiFi berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data paket WiFi (Admin)
     */
    public function edit($id)
    {
        $paketWifi = $this->findPaketWifiOrFail($id);

        return view('dashboard.datapaket.edit', compact('paketWifi'));
    }

    /**
     * Menampilkan form untuk mengedit data paket WiFi (SuperAdmin)
     */
    public function editSuperAdmin($id)
    {
        $paketWifi = $this->findPaketWifiOrFail($id);

        return view('superadmin.datapaket.edit', compact('paketWifi'));
    }

    /**
     * Memperbarui data paket WiFi di database (Admin)
     */
    public function update(Request $request, $id)
    {
        $this->validatePaketWifi($request);

        $paketWifi = PaketWifi::findOrFail($id);

        $this->updatePaketWifi($request, $paketWifi);

        return redirect()->route('dashboard.datapaket.index')
            ->with('success', 'Paket WiFi berhasil diperbarui.');
    }

    /**
     * Memperbarui data paket WiFi di database (SuperAdmin)
     */
    public function updateSuperAdmin(Request $request, $id)
    {
        $this->validatePaketWifi($request);

        $paketWifi = PaketWifi::findOrFail($id);

        $this->updatePaketWifi($request, $paketWifi);

        return redirect()->route('superadmin.datapaket.index')
            ->with('success', 'Paket WiFi berhasil diperbarui.');
    }

    /**
     * Menghapus data paket WiFi dari database (Admin)
     */
    public function destroy($id)
    {
        $paketWifi = PaketWifi::findOrFail($id);

        $this->deletePaketWifi($paketWifi);

        return redirect()->route('dashboard.datapaket.index')
            ->with('success', 'Paket WiFi berhasil dihapus.');
    }

    /**
     * Menghapus data paket WiFi dari database (SuperAdmin)
     */
    public function destroySuperAdmin($id)
    {
        $paketWifi = PaketWifi::findOrFail($id);

        $this->deletePaketWifi($paketWifi);

        return redirect()->route('superadmin.datapaket.index')
            ->with('success', 'Paket WiFi berhasil dihapus.');
    }

    // ==================== PRIVATE METHODS ====================

    /**
     * Validasi data paket WiFi
     */
    private function validatePaketWifi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:255',
            'harga_bulanan' => 'required|numeric',
            'deskripsi_paket' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Menangani upload gambar
     */
    private function handleImageUpload(Request $request)
    {
        if ($request->hasFile('gambar')) {
            return $request->file('gambar')->store('images', 'public');
        }

        return null;
    }

    /**
     * Membuat paket WiFi baru
     */
    private function createPaketWifi(Request $request, $gambarPath)
    {
        return PaketWifi::create([
            'nama_paket' => $request->nama_paket,
            'kecepatan' => $request->kecepatan,
            'harga_bulanan' => $request->harga_bulanan,
            'deskripsi_paket' => $request->deskripsi_paket,
            'gambar' => $gambarPath,
        ]);
    }

    /**
     * Memperbarui paket WiFi
     */
    private function updatePaketWifi(Request $request, PaketWifi $paketWifi)
    {
        $gambarPath = $paketWifi->gambar;

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($paketWifi->gambar) {
                Storage::delete('public/' . $paketWifi->gambar);
            }

            $gambarPath = $request->file('gambar')->store('images', 'public');
        }

        $paketWifi->update([
            'nama_paket' => $request->nama_paket,
            'kecepatan' => $request->kecepatan,
            'harga_bulanan' => $request->harga_bulanan,
            'deskripsi_paket' => $request->deskripsi_paket,
            'gambar' => $gambarPath,
        ]);
    }

    /**
     * Menghapus paket WiFi beserta gambarnya
     */
    private function deletePaketWifi(PaketWifi $paketWifi)
    {
        // Hapus gambar jika ada
        if ($paketWifi->gambar) {
            Storage::delete('public/' . $paketWifi->gambar);
        }

        $paketWifi->delete();
    }

    /**
     * Mencari paket WiFi berdasarkan ID atau gagal
     */
    private function findPaketWifiOrFail($id)
    {
        $paketWifi = PaketWifi::find($id);

        if (!$paketWifi) {
            abort(404, 'Paket WiFi tidak ditemukan');
        }

        return $paketWifi;
    }
}