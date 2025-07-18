<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\User;
use App\Models\PaketWifi;
use App\Models\StatusTagihan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar semua tagihan
     */
    public function index()
    {
        $tagihan = Tagihan::with(['user', 'paketWifi', 'statusTagihan', 'pengajuan', ])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Tambahkan pagination untuk performa yang lebih baik

       
        // Menghitung jumlah tagihan yang sudah dibayar (status 'Dibayar' atau 'Lunas')
        $lunas = Tagihan::where('status_tagihan_id', 3)->count(); // Status 'Dibayar' atau 'Lunas'

        return view('dashboard.datatagihan.index', compact('tagihan'));
    }

    public function datatagihanSuperAdmin()
    {
        $tagihan = Tagihan::with(['user', 'paketWifi', 'statusTagihan', 'pengajuan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Tambahkan pagination untuk performa yang lebih baik
        
       
        // Menghitung jumlah tagihan yang sudah dibayar (status 'Dibayar' atau 'Lunas')
        $lunas = Tagihan::where('status_tagihan_id', 3)->count(); // Status 'Dibayar' atau 'Lunas'

        return view('superadmin.datatagihan.index', compact('tagihan'));
    }

    /**
     * Menampilkan form untuk membuat tagihan baru
     */
      public function create()
{
    $users = User::all();
    $paket_wifi = PaketWifi::all();
    $status_tagihan = StatusTagihan::all();
    
    // Ambil data paket wifi berdasarkan paket_wifi_id_paket_wifi
    $paket_wifi_data = PaketWifi::where('id_paket_wifi', '!=', null)
        ->with(['pengajuan', 'user']) // Eager loading relasi jika ada
        ->get();

    return view('dashboard.datatagihan.create', compact('users', 'paket_wifi', 'status_tagihan', 'paket_wifi_data'));
}
    public function createSuperAdmin()
{
    $users = User::all();
    $paket_wifi = PaketWifi::all();
    $status_tagihan = StatusTagihan::all();
    
    // Ambil data paket wifi berdasarkan paket_wifi_id_paket_wifi
    $paket_wifi_data = PaketWifi::where('id_paket_wifi', '!=', null)
        ->with(['pengajuan', 'user']) // Eager loading relasi jika ada
        ->get();

    return view('superadmin.datatagihan.create', compact('users', 'paket_wifi', 'status_tagihan', 'paket_wifi_data'));
}

public function createTeknisi()
{
    $users = User::all();
    $paket_wifi = PaketWifi::all();
    $status_tagihan = StatusTagihan::all();
    
    // Ambil data paket wifi berdasarkan paket_wifi_id_paket_wifi
    $paket_wifi_data = PaketWifi::where('id_paket_wifi', '!=', null)
        ->with(['pengajuan', 'user']) // Eager loading relasi jika ada
        ->get();

    return view('teknisi.datatagihan.create', compact('users', 'paket_wifi', 'status_tagihan', 'paket_wifi_data'));
}
    /**
     * Menyimpan tagihan baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'users_id_user' => 'required|exists:users,id_user',
            'paket_wifi_id_paket_wifi' => 'required|exists:paket_wifi,id_paket_wifi',
            'pengajuan_id' => 'required|exists:pengajuan,id',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date|after:onemonth', // Pastikan jatuh tempo setelah hari ini
            'status_tagihan_id' => 'required|exists:status_tagihan,id_status_tagihan',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah pengajuan sudah memiliki tagihan
            $existingTagihan = Tagihan::where('pengajuan_id', $request->pengajuan_id)->first();
            if ($existingTagihan) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Pengajuan ini sudah memiliki tagihan');
            }

            // Ambil pengajuan spesifik berdasarkan ID
            $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);

            // Pastikan pengajuan berstatus selesai
            if ($pengajuan->status_pengajuan_id != 5) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Pengajuan belum selesai');
            }

            Tagihan::create([
                'users_id_user' => $request->users_id_user,
                'paket_wifi_id_paket_wifi' => $request->paket_wifi_id_paket_wifi,
                'jumlah_tagihan' => $request->jumlah_tagihan,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status_tagihan_id' => $request->status_tagihan_id,
                'pengajuan_id' => $request->pengajuan_id,
            ]);

            DB::commit();
            return redirect()->route('dashboard.datatagihan.index')
                ->with('success', 'Tagihan berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error membuat tagihan: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat tagihan. Silakan coba lagi.');
        }
    }



    public function storeSuperAdmin(Request $request)
    {
        $validated = $request->validate([
            'users_id_user' => 'required|exists:users,id_user',
            'paket_wifi_id_paket_wifi' => 'required|exists:paket_wifi,id_paket_wifi',
            'pengajuan_id' => 'required|exists:pengajuan,id',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date|after:today', // Pastikan jatuh tempo setelah hari ini
            'status_tagihan_id' => 'required|exists:status_tagihan,id_status_tagihan',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah pengajuan sudah memiliki tagihan
            $existingTagihan = Tagihan::where('pengajuan_id', $request->pengajuan_id)->first();
            if ($existingTagihan) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Pengajuan ini sudah memiliki tagihan');
            }

            // Ambil pengajuan spesifik berdasarkan ID
            $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);

            // Pastikan pengajuan berstatus selesai
            if ($pengajuan->status_pengajuan_id != 5) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Pengajuan belum selesai');
            }

            Tagihan::create([
                'users_id_user' => $request->users_id_user,
                'paket_wifi_id_paket_wifi' => $request->paket_wifi_id_paket_wifi,
                'jumlah_tagihan' => $request->jumlah_tagihan,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status_tagihan_id' => $request->status_tagihan_id,
                'pengajuan_id' => $request->pengajuan_id,
            ]);

            DB::commit();
            return redirect()->route('superadmin.datatagihan.index')
                ->with('success', 'Tagihan berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error membuat tagihan: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat tagihan. Silakan coba lagi.');
        }
    }


 



    /**
     * Membuat tagihan otomatis ketika pengajuan selesai
     * Method ini dipanggil dari PengajuanController atau Observer
     */

    /**
     * Menghitung tanggal tagihan
     * Dapat dikustomisasi sesuai kebijakan bisnis
     */
   
    /**
     * Menampilkan detail tagihan spesifik
     */
    public function show(string $id)
    {
        $tagihan = Tagihan::with(['user', 'paketWifi', 'statusTagihan', 'pengajuan',])
            ->findOrFail($id);
        return view('dashboard.datatagihan.show', compact('tagihan'));
    }


    public function showSuperAdmin(string $id)
    {
        $tagihan = Tagihan::with(['user', 'paketWifi', 'statusTagihan', 'pengajuan'])
            ->findOrFail($id);
        return view('superadmin.datatagihan.show', compact('tagihan'));
    }

    /**
     * Menampilkan form edit tagihan
     */
    public function edit(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $users = User::all();
        $paket_wifi = PaketWifi::all();
        $status_tagihan = StatusTagihan::all();

        // Ambil pengajuan yang selesai, termasuk yang sudah dikaitkan dengan tagihan ini
        $pengajuan = Pengajuan::where('status_pengajuan_id', 5)
            ->where(function($query) use ($tagihan) {
                $query->whereDoesntHave('tagihan')
                      ->orWhere('id', $tagihan->pengajuan_id);
            })
            ->get();

        return view('dashboard.datatagihan.edit', compact('tagihan', 'users', 'paket_wifi', 'status_tagihan', 'pengajuan'));
    }


    public function editSuperAdmin(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $users = User::all();
        $paket_wifi = PaketWifi::all();
        $status_tagihan = StatusTagihan::all();

        // Ambil pengajuan yang selesai, termasuk yang sudah dikaitkan dengan tagihan ini
        $pengajuan = Pengajuan::where('status_pengajuan_id', 5)
            ->where(function($query) use ($tagihan) {
                $query->whereDoesntHave('tagihan')
                      ->orWhere('id', $tagihan->pengajuan_id);
            })
            ->get();

        return view('superadmin.datatagihan.edit', compact('tagihan', 'users', 'paket_wifi', 'status_tagihan', 'pengajuan'));
    }

    /**
     * Memperbarui tagihan
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'users_id_user' => 'required|exists:users,id_user',
            'paket_wifi_id_paket_wifi' => 'required|exists:paket_wifi,id_paket_wifi',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
            'status_tagihan_id' => 'required|exists:status_tagihan,id_status_tagihan',
        ]);

        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($id);
            $tagihan->update([
                'users_id_user' => $request->users_id_user,
                'paket_wifi_id_paket_wifi' => $request->paket_wifi_id_paket_wifi,
                'jumlah_tagihan' => $request->jumlah_tagihan,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status_tagihan_id' => $request->status_tagihan_id,
            ]);

            DB::commit();
            return redirect()->route('dashboard.datatagihan.index')
                ->with('success', 'Tagihan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error memperbarui tagihan: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui tagihan. Silakan coba lagi.');
        }
    }


    public function updateSuperAdmin(Request $request, string $id)
    {
        $validated = $request->validate([
            'users_id_user' => 'required|exists:users,id_user',
            'paket_wifi_id_paket_wifi' => 'required|exists:paket_wifi,id_paket_wifi',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
            'status_tagihan_id' => 'required|exists:status_tagihan,id_status_tagihan',
        ]);

        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($id);
            $tagihan->update([
                'users_id_user' => $request->users_id_user,
                'paket_wifi_id_paket_wifi' => $request->paket_wifi_id_paket_wifi,
                'jumlah_tagihan' => $request->jumlah_tagihan,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status_tagihan_id' => $request->status_tagihan_id,
            ]);

            DB::commit();
            return redirect()->route('superadmin.datatagihan.index')
                ->with('success', 'Tagihan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error memperbarui tagihan: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui tagihan. Silakan coba lagi.');
        }
    }


    /**
     * Menghapus tagihan
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($id);
            
            // Cek apakah tagihan sudah dibayar
            if ($tagihan->status_tagihan_id == 2) { // Asumsi ID 2 = sudah bayar
                return redirect()->back()
                    ->with('error', 'Tagihan yang sudah dibayar tidak dapat dihapus');
            }

            $tagihan->delete();

            DB::commit();
            return redirect()->route('dashboard.datatagihan.index')
                ->with('success', 'Tagihan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error menghapus tagihan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus tagihan. Silakan coba lagi.');
        }
    }


    public function destroySuperAdmin(string $id)
    {
        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($id);
            
            // Cek apakah tagihan sudah dibayar
            if ($tagihan->status_tagihan_id == 2) { // Asumsi ID 2 = sudah bayar
                return redirect()->back()
                    ->with('error', 'Tagihan yang sudah dibayar tidak dapat dihapus');
            }

            $tagihan->delete();

            DB::commit();
            return redirect()->route('superadmin.datatagihan.index')
                ->with('success', 'Tagihan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error menghapus tagihan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus tagihan. Silakan coba lagi.');
        }
    }











public function userTagihan()
{
    try {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $tagihan = Tagihan::with(['paketWifi', 'statusTagihan', 'pengajuan'])
            ->where('users_id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.tagihan', compact('tagihan'));

    } catch (\Exception $e) {
        Log::error('Error menampilkan tagihan user: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat tagihan.');
    }
}

/**
 * Menampilkan detail tagihan untuk user
 */
public function userTagihanShow($id)
{
    try {
        $user = auth()->user();
        
        $tagihan = Tagihan::with(['paketWifi', 'statusTagihan', 'pengajuan', 'user'])
            ->where('id', $id)
            ->where('users_id_user', $user->id_user)
            ->first();

        if (!$tagihan) {
            return redirect()->route('user.tagihan')->with('error', 'Tagihan tidak ditemukan.');
        }

        return view('user.tagihan.show', compact('tagihan'));

    } catch (\Exception $e) {
        Log::error('Error menampilkan detail tagihan user: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat detail tagihan.');
    }
}

/**
 * Menampilkan halaman pembayaran tagihan
 */
public function userTagihanPayment($id)
{
    try {
        $user = auth()->user();
        
        $tagihan = Tagihan::with(['paketWifi', 'statusTagihan', 'pengajuan'])
            ->where('id', $id)
            ->where('users_id_user', $user->id_user)
            ->whereIn('status_tagihan_id', [1, 2]) // Hanya tagihan yang belum dibayar atau terlambat
            ->first();

        if (!$tagihan) {
            return redirect()->route('user.tagihan')->with('error', 'Tagihan tidak ditemukan atau sudah dibayar.');
        }

        return view('user.tagihan.payment', compact('tagihan'));

    } catch (\Exception $e) {
        Log::error('Error menampilkan halaman pembayaran: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman pembayaran.');
    }
}



    





   public function updateStatusSuperAdmin(Request $request, $id)
{
    try {
        $tagihan = Tagihan::findOrFail($id);
        
        // Cek jika tagihan sudah lunas
        if ($tagihan->status_tagihan_id == 3) { // 3 adalah status "Lunas"
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        // Mengubah status tagihan menjadi 'Lunas' (ID 3)
        $tagihan->update([
            'status_tagihan_id' => 3, // ID untuk "Lunas"
            'bulan_tagihan' => Carbon::now()->addMonth()->format('Y-m'), // Ubah bulan tagihan ke bulan depan
            'tanggal_tagihan' => Carbon::now()->addMonth()->startOfMonth(), // Tanggal tagihan di set ke awal bulan depan
        ]);

        return redirect()->route('superadmin.datatagihan.index')
                         ->with('success', 'Tagihan berhasil diperbarui menjadi Lunas, bulan tagihan diubah ke bulan depan.');

    } catch (\Exception $e) {
        Log::error('Gagal memperbarui status tagihan: ' . $e->getMessage(), [
            'tagihan_id' => $id
        ]);
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status tagihan.');
    }
}


public function generateTagihanManual($pengajuanId)
{
    try {
        $pengajuan = Pengajuan::with(['paketWifi', 'user'])->find($pengajuanId);
        
        if (!$pengajuan) {
            return [
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan'
            ];
        }

        $result = self::createAutoTagihan($pengajuan);
        
        if ($result !== false) {
            return [
                'success' => true,
                'message' => 'Tagihan berhasil dibuat',
                'data' => $result
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal membuat tagihan'
            ];
        }

    } catch (\Exception $e) {
        Log::error("Error generate tagihan manual untuk pengajuan {$pengajuanId}: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ];
    }
}

/**
 * Cek tagihan yang akan jatuh tempo dalam beberapa hari ke depan
 */
public function getTagihanJatuhTempo($hari = 7)
{
    try {
        // Tentukan tanggal batas jatuh tempo
        $tanggalBatas = Carbon::now()->addDays($hari);
        
        // Ambil tagihan yang jatuh tempo dalam rentang waktu tertentu
        $tagihan = Tagihan::with(['user:id,nama,email', 'paketWifi:id,nama_paket'])
            ->where('status_tagihan_id', 1) // Tagihan yang belum dibayar
            ->whereBetween('jatuh_tempo', [Carbon::now(), $tanggalBatas]) // Filter jatuh tempo antara sekarang dan tanggal batas
            ->orderBy('jatuh_tempo', 'asc')
            ->get();

        // Hitung total tagihan
        $total = $tagihan->count();

        // Kembalikan data
        return [
            'success' => true,
            'data' => $tagihan,
            'total' => $total
        ];

    } catch (\Exception $e) {
        // Log error yang lebih detail untuk debugging
        Log::error('Error mengambil tagihan jatuh tempo: ' . $e->getMessage(), [
            'exception' => $e
        ]);

        return [
            'success' => false,
            'message' => 'Gagal mengambil data tagihan jatuh tempo.'
        ];
    }
}






public static function createAutoTagihan($pengajuan)
{
    try {
        // Mulai transaksi
        DB::beginTransaction();

        // Cek apakah pengajuan sudah memiliki tagihan
        $existingTagihan = Tagihan::where('pengajuan_id', $pengajuan->id)->first();
        if ($existingTagihan) {
            Log::info("Pengajuan ID {$pengajuan->id} sudah memiliki tagihan. Tidak perlu membuat tagihan baru.");
            return $existingTagihan; // Jika sudah ada tagihan, kembalikan tagihan yang sudah ada
        }

        // Pastikan pengajuan memiliki relasi paket wifi
        if (!$pengajuan->paketWifi) {
            DB::rollBack();
            Log::error("Pengajuan ID {$pengajuan->id} tidak memiliki paket wifi.");
            return false;
        }

        // Validasi status pengajuan - hanya proses jika status selesai
        if ($pengajuan->status_pengajuan_id != 5) {
            DB::rollBack();
            Log::warning("Pengajuan ID {$pengajuan->id} belum selesai (status: {$pengajuan->status_pengajuan_id}).");
            return false;
        }

        // Cek apakah tanggal penyelesaian pengajuan valid
        $tanggalSelesai = $pengajuan->updated_at;
        if (!$tanggalSelesai instanceof Carbon) {
            $tanggalSelesai = Carbon::parse($tanggalSelesai);
        }

        // Menghitung tanggal tagihan dan jatuh tempo
        $tanggalTagihan = self::hitungTanggalTagihan($tanggalSelesai);
        $jatuhTempo = self::hitungJatuhTempo($tanggalTagihan); // Jatuh tempo dihitung berdasarkan tanggal tagihan

        // Buat tagihan baru
        $tagihan = Tagihan::create([
            'users_id_user' => $pengajuan->users_id_user,
            'paket_wifi_id_paket_wifi' => $pengajuan->paket_wifi_id_paket_wifi,
            'jumlah_tagihan' => $pengajuan->paketWifi->harga,
            'tanggal_tagihan' => $tanggalTagihan,
            'jatuh_tempo' => $jatuhTempo,
            'status_tagihan_id' => 1, // Status belum bayar
            'pengajuan_id' => $pengajuan->id,
        ]);

        // Commit transaksi
        DB::commit();
        Log::info("Tagihan otomatis berhasil dibuat untuk pengajuan ID: {$pengajuan->id}, Tagihan ID: {$tagihan->id}");
        return $tagihan;

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error membuat tagihan otomatis untuk pengajuan ID {$pengajuan->id}: " . $e->getMessage());
        return false;
    }
}

/**
 * Fungsi untuk menghitung tanggal tagihan pertama (berdasarkan penyelesaian pengajuan)
 */
private static function hitungTanggalTagihan($tanggalSelesai)
{
    // Validasi input
    if (!$tanggalSelesai instanceof Carbon) {
        throw new \InvalidArgumentException("Tanggal selesai pengajuan tidak valid.");
    }

    $day = $tanggalSelesai->day;

    // Tentukan tanggal tagihan berdasarkan rentang tanggal penyelesaian
    if ($day >= 1 && $day <= 9) {
        // Tagihan pada tanggal 10 bulan depan
        return $tanggalSelesai->copy()->addMonth()->day(10);
    } elseif ($day >= 10 && $day <= 14) {
        // Tagihan pada tanggal 15 bulan depan
        return $tanggalSelesai->copy()->addMonth()->day(15);
    } elseif ($day >= 15 && $day <= 19) {
        // Tagihan pada tanggal 20 bulan depan
        return $tanggalSelesai->copy()->addMonth()->day(20);
    } elseif ($day >= 20 && $day <= 24) {
        // Tagihan pada tanggal 25 bulan depan
        return $tanggalSelesai->copy()->addMonth()->day(25);
    } elseif ($day == 25) {
        // Khusus untuk tanggal 25, tagihan pada tanggal 30 bulan depan
        return $tanggalSelesai->copy()->addMonth()->day(30);
    } elseif ($day >= 26 && $day <= 31) {
        // Tagihan pada tanggal 5 bulan berikutnya (2 bulan dari sekarang)
        return $tanggalSelesai->copy()->addMonths(2)->day(5);
    } else {
        // Fallback - seharusnya tidak pernah terjadi
        Log::warning("Tanggal tidak terduga: {$day}, menggunakan tanggal 1 bulan depan");
        return $tanggalSelesai->copy()->addMonth()->startOfMonth();
    }
}

/**
 * Fungsi untuk menghitung jatuh tempo berdasarkan tanggal tagihan
 */
private static function hitungJatuhTempo($tanggalTagihan)
{
    // Validasi input
    if (!$tanggalTagihan instanceof Carbon) {
        throw new \InvalidArgumentException("Tanggal tagihan tidak valid.");
    }

    // Default: 30 hari dari tanggal tagihan
    return $tanggalTagihan->copy()->addDays(30); // 30 hari setelah tanggal tagihan
}

/**
 * Fungsi untuk menghitung tanggal tagihan berikutnya (untuk tagihan bulanan rekurring)
 */
private static function hitungTanggalTagihanBerikutnya($tanggalSelesai)
{
    $today = Carbon::now();

    // Cari tagihan terakhir untuk pengajuan ini
    $tagihan = Tagihan::where('pengajuan_id', $pengajuan->id ?? null)
                     ->orderBy('tanggal_tagihan', 'desc')
                     ->first();

    if ($tagihan) {
        // Jika sudah ada tagihan sebelumnya, tagihan berikutnya adalah bulan depan
        $tanggalTagihanTerakhir = Carbon::parse($tagihan->tanggal_tagihan);
        return $tanggalTagihanTerakhir->copy()->addMonth();
    }

    // Jika belum ada tagihan, gunakan logika awal
    return self::hitungTanggalTagihan($tanggalSelesai);
}

private function createNextMonthTagihan($tagihan)
{
    try {
        // Pastikan tagihan sudah lunas
        if ($tagihan->status_tagihan_id != 3) {
            return false;
        }

        // Ambil data pengajuan terkait
        $pengajuan = $tagihan->pengajuan;

        if (!$pengajuan) {
            Log::warning("Pengajuan tidak ditemukan untuk tagihan ID {$tagihan->id_tagihan}");
            return false;
        }

        // Menghitung tanggal tagihan berikutnya
        $tanggalTagihanBerikutnya = Carbon::parse($tagihan->tanggal_tagihan)->addMonth()->startOfMonth();
        $jatuhTempo = $tanggalTagihanBerikutnya->copy()->addDays(30); // Jatuh tempo 30 hari setelah tanggal tagihan

        // Membuat tagihan baru untuk bulan berikutnya
        $newTagihan = Tagihan::create([
            'users_id_user' => $tagihan->users_id_user,
            'paket_wifi_id_paket_wifi' => $tagihan->paket_wifi_id_paket_wifi,
            'jumlah_tagihan' => $tagihan->jumlah_tagihan, // Menggunakan jumlah tagihan yang sama
            'tanggal_tagihan' => $tanggalTagihanBerikutnya,
            'jatuh_tempo' => $jatuhTempo,
            'status_tagihan_id' => 1, // Status baru: Belum Dibayar
            'pengajuan_id' => $pengajuan->id,
        ]);

        Log::info("Tagihan baru berhasil dibuat untuk bulan depan, ID: {$newTagihan->id_tagihan}");

        return true;

    } catch (\Exception $e) {
        Log::error('Error membuat tagihan untuk bulan depan: ' . $e->getMessage());
        return false;
    }
}


}


