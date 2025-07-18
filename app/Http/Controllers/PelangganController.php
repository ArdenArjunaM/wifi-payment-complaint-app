<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaketWifi;
use App\Models\DataPelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan untuk Admin
     */
    public function index(Request $request)
    {
        $pelanggan = $this->getPelangganWithFilters($request);
        
        return view('dashboard.datapelanggan.index', compact('pelanggan'));
    }

    /**
     * Menampilkan daftar pelanggan untuk SuperAdmin
     */
    public function datapelanggan(Request $request)
    {
        $pelanggan = $this->getPelangganWithFilters($request);
        
        return view('superadmin.datapelanggan.index', compact('pelanggan'));
    }

    /**
     * Menampilkan form tambah pelanggan untuk Admin
     */
    public function create()
    {
        $data = $this->getPaketWifiData();
        
        return view('dashboard.datapelanggan.create', $data);
    }

    /**
     * Menampilkan form tambah pelanggan untuk SuperAdmin
     */
    public function createSuperAdmin()
    {
        $data = $this->getPaketWifiData();
        
        return view('superadmin.datapelanggan.create', $data);
    }

    /**
     * Menyimpan data pelanggan baru untuk Admin
     */
    public function store(Request $request)
    {
        $this->validatePelanggan($request);
        
        $this->createPelanggan($request);

        return redirect()->route('dashboard.datapelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Menyimpan data pelanggan baru untuk SuperAdmin
     */
    public function storeSuperAdmin(Request $request)
    {
        $this->validatePelanggan($request);
        
        $this->createPelanggan($request);

        return redirect()->route('superadmin.datapelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit pelanggan untuk Admin
     */
    public function edit($id)
    {
        $pelanggan = User::findOrFail($id);
        
        return view('dashboard.datapelanggan.edit', compact('pelanggan'));
    }

    /**
     * Menampilkan form edit pelanggan untuk SuperAdmin
     */
    public function editSuperAdmin($id)
    {
        $pelanggan = User::with('paketWifi')->findOrFail($id);
        $data = $this->getPaketWifiData();
        
        return view('superadmin.datapelanggan.edit', 
            array_merge(compact('pelanggan'), $data));
    }

    /**
     * Mengupdate data pelanggan untuk Admin
     */
    public function update(Request $request, $id)
    {
        $this->validatePelangganUpdate($request, $id);
        
        $this->updatePelanggan($request, $id);

        return redirect()->route('dashboard.datapelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    /**
     * Mengupdate data pelanggan untuk SuperAdmin
     */
    public function updateSuperAdmin(Request $request, $id)
    {
        $this->validatePelangganSuperAdminUpdate($request, $id);
        
        $this->updatePelangganSuperAdmin($request, $id);

        return redirect()->route('superadmin.datapelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    /**
     * Menghapus pelanggan untuk Admin
     */
    public function destroy($id)
    {
        $pelanggan = User::findOrFail($id);
        $pelanggan->delete();
        
        return redirect()->route('dashboard.datapelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Menghapus pelanggan untuk SuperAdmin
     */
    public function destroySuperAdmin($id)
    {
        $pelanggan = User::findOrFail($id);
        $pelanggan->delete();
        
        return redirect()->route('superadmin.datapelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Menampilkan detail pelanggan untuk SuperAdmin
     */
    public function showSuperAdmin($id)
    {
        $pelanggan = DataPelanggan::with('pengajuan', 'tagihan')->findOrFail($id);

        return view('superadmin.datapelanggan.show', compact('pelanggan'));
    }

    // ==================== PRIVATE METHODS ====================

    /**
     * Mendapatkan data pelanggan dengan filter
     */
    private function getPelangganWithFilters(Request $request)
    {
        $query = User::with(['pengajuan.statusPengajuan', 'pengajuan.paketWifi'])
                     ->whereNotIn('role_id', ['1', '4', '3']);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('no_hp', 'LIKE', "%{$search}%");
            });
        }

        // Filter berdasarkan status pengajuan
        if ($request->filled('status')) {
            $this->applyStatusFilter($query, $request->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Menerapkan filter status pengajuan
     */
    private function applyStatusFilter($query, $status)
    {
        if ($status === 'Belum Mengajukan') {
            $query->whereDoesntHave('pengajuan');
        } else {
            $query->whereHas('pengajuan', function($q) use ($status) {
                $q->whereHas('statusPengajuan', function($sq) use ($status) {
                    $sq->where('status', $status);
                })
                ->join('pengajuan as p2', 'pengajuan.users_id_user', '=', 'p2.users_id_user')
                ->whereRaw('p2.created_at = (SELECT MAX(created_at) FROM pengajuan WHERE users_id_user = p2.users_id_user)')
                ->select('pengajuan.*');
            });
        }
    }

    /**
     * Mendapatkan data paket WiFi
     */
    private function getPaketWifiData()
    {
        $paketWifi = PaketWifi::all();
        $totalPaket = $paketWifi->count();
        
        return compact('paketWifi', 'totalPaket');
    }

    /**
     * Validasi data pelanggan untuk create
     */
    private function validatePelanggan(Request $request)
    {
        return $request->validate([
            'username' => 'required|string|max:10|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'role' => 'required|in:user',
        ]);
    }

    /**
     * Validasi data pelanggan untuk update
     */
    private function validatePelangganUpdate(Request $request, $id)
    {
        return $request->validate([
            'username' => 'required|string|max:10|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'role' => 'required|in:user,admin',
        ]);
    }

    /**
     * Validasi data pelanggan untuk update SuperAdmin
     */
    private function validatePelangganSuperAdminUpdate(Request $request, $id)
    {
        return $request->validate([
            'username' => 'required|string|max:10|unique:users,username,' . $id . ',id_user',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'password' => 'nullable|string|min:8|confirmed',
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'paket' => 'nullable|exists:paket_wifi,id_paket_wifi',
            'status' => 'required|in:aktif,non-aktif',
        ]);
    }

    /**
     * Membuat pelanggan baru
     */
    private function createPelanggan(Request $request)
    {
        return User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'status' => 'belum berlangganan',
        ]);
    }

    /**
     * Mengupdate data pelanggan
     */
    private function updatePelanggan(Request $request, $id)
    {
        $pelanggan = User::findOrFail($id);

        $updateData = [
            'username' => $request->username,
            'email' => $request->email,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        return $pelanggan->update($updateData);
    }

    /**
     * Mengupdate data pelanggan untuk SuperAdmin
     */
    private function updatePelangganSuperAdmin(Request $request, $id)
    {
        $pelanggan = User::findOrFail($id);

        $updateData = [
            'username' => $request->username,
            'email' => $request->email,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        if ($request->filled('paket')) {
            $updateData['paket_wifi_id'] = $request->paket;
        }

        return $pelanggan->update($updateData);
    }
}