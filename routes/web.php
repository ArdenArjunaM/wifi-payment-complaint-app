<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaketWifiController;
use App\Http\Controllers\TotalUserController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserTagihanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ===== WEB PROFILE ROUTES =====
Route::get('/', function () {
    return view('home');
});

Route::get('/paket', function () {
    return view('paket');
});

Route::get('/promo', function () {
    return view('promo');
});

Route::get('/lokasi', function () {
    return view('lokasi');
});

Route::get('/tentang', function () {
    return view('tentangkami');
});

// ===== AUTHENTICATION ROUTES =====
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ===== ADMIN ROUTES =====
Route::get('/admin', function () {
    return view('/dashboard/admin');
});

Route::get('/admin', [AuthController::class, 'adminDashboard'])->middleware(['auth', 'role:admin'])->name('admin.dashboard');

// ===== DATA PAKET WIFI ROUTES =====
Route::get('/datapaket', [PaketWifiController::class, 'index'])->name('dashboard.datapaket.index');
Route::get('/datapaket/create', [PaketWifiController::class, 'create'])->name('dashboard.datapaket.create');
Route::post('/datapaket', [PaketWifiController::class, 'store'])->name('dashboard.datapaket.store');
Route::get('/datapaket/edit/{id}', [PaketWifiController::class, 'edit'])->name('dashboard.datapaket.edit');
Route::put('/datapaket/{id}', [PaketWifiController::class, 'update'])->name('dashboard.datapaket.update');
Route::delete('/datapaket/{id}', [PaketWifiController::class, 'destroy'])->name('dashboard.datapaket.destroy');

// ===== DATA PELANGGAN ROUTES =====
Route::get('/datapelanggan', [PelangganController::class, 'index'])->name('dashboard.datapelanggan.index');
Route::get('/datapelanggan/create', [PelangganController::class, 'create'])->name('dashboard.datapelanggan.create');
Route::post('/datapelanggan', [PelangganController::class, 'store'])->name('dashboard.datapelanggan.store');
Route::get('/datapelanggan/{id}', [PelangganController::class, 'show'])->name('dashboard.datapelanggan.show');
Route::get('/datapelanggan/edit/{id}', [PelangganController::class, 'edit'])->name('dashboard.datapelanggan.edit');
Route::put('/datapelanggan/{id}', [PelangganController::class, 'update'])->name('dashboard.datapelanggan.update');
Route::delete('/datapelanggan/{id}', [PelangganController::class, 'destroy'])->name('dashboard.datapelanggan.destroy');

// ===== TAGIHAN ROUTES =====
Route::resource('tagihan', TagihanController::class);

// Route khusus dengan prefix /datatagihan
Route::get('/datatagihan', [TagihanController::class, 'index'])->name('dashboard.datatagihan.index');
Route::get('/datatagihan/create', [TagihanController::class, 'create'])->name('dashboard.datatagihan.create');
Route::get('/datatagihan/{id}', [TagihanController::class, 'show'])->name('dashboard.datatagihan.show'); // DIPERBAIKI
Route::post('/datatagihan', [TagihanController::class, 'store'])->name('dashboard.datatagihan.store');
Route::get('/datatagihan/{id}/edit', [TagihanController::class, 'edit'])->name('dashboard.datatagihan.edit');
Route::put('/datatagihan/{id}', [TagihanController::class, 'update'])->name('dashboard.datatagihan.update');
Route::delete('/datatagihan/{id}', [TagihanController::class, 'destroy'])->name('dashboard.datatagihan.destroy');
Route::get('/datatagihan/{id}/kirim', [TagihanController::class, 'kirim'])->name('dashboard.datatagihan.kirim');
Route::patch('/datatagihan/{id}/mark-as-paid', [TagihanController::class, 'markAsPaid'])->name('dashboard.datatagihan.markAsPaid');
Route::post('/generate-monthly', [TagihanController::class, 'generateMonthly'])->name('dashboard.datatagihan.generateMonthly');

// Route dashboard
Route::get('/dashboard/datatagihan', [TagihanController::class, 'index'])->name('dashboard.dashboard.datatagihan.index');
Route::post('/dashboard/datatagihan/generate-monthly', [TagihanController::class, 'generateMonthlyBills'])->name('dashboard.dashboard.datatagihan.generateMonthly');
Route::patch('/dashboard/datatagihan/{id}/mark-paid', [TagihanController::class, 'markAsPaid'])->name('dashboard.dashboard.datatagihan.markAsPaid');
Route::get('/dashboard/datatagihan/{id}/kirim', [TagihanController::class, 'kirim'])->name('dashboard.dashboard.datatagihan.kirim');

// ===== PAYMENT ROUTES =====
Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/finish', [PaymentController::class, 'paymentFinish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');
Route::get('/payment/show/{tagihanId}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/process/{tagihanId}', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/status/{orderId}', [PaymentController::class, 'checkPaymentStatus'])->name('payment.status');
Route::post('/payment/update-status/{orderId}', [PaymentController::class, 'updatePaymentStatus'])->name('payment.update-status');
Route::post('/payment/update-tagihan-status/{tagihanId}', [PaymentController::class, 'updateTagihanStatusManual'])->name('payment.update-tagihan-status');


// ===== PENGAJUAN ROUTES =====
Route::resource('pengajuan', PengajuanController::class);
Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('dashboard.pengajuan.create');
Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('dashboard.pengajuan.index');
Route::get('/pengajuan/edit/{id}', [PengajuanController::class, 'edit'])->name('dashboard.pengajuan.edit');
Route::put('/pengajuan/update/{id}', [PengajuanController::class, 'update'])->name('dashboard.pengajuan.update');
Route::delete('/pengajuan/destroy/{id}', [PengajuanController::class, 'destroy'])->name('dashboard.pengajuan.destroy');
Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])->name('dashboard.pengajuan.show');
Route::patch('/pengajuan/{id}/selesai', [PengajuanController::class, 'selesai'])->name('dashboard.pengajuan.selesai');

// ===== DASHBOARD ROUTES =====
Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->middleware(['auth'])->name('dashboard');
Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->middleware(['role:admin'])->name('dashboard.admin');
Route::get('/dashboard', function () {
    return view('user.dashboard');
});
Route::get('/dashboard', [AuthController::class, 'userDashboard'])->middleware(['auth', 'role:user'])->name('user.dashboard');
Route::get('/teknisi/dashboard', [AuthController::class, 'teknisiDashboard'])->middleware(['auth', 'role:teknisi'])->name('teknisi.dashboard');

// ===== USER ROUTES =====
Route::get('/paketwifi', function () {
    return view('user.paketwifi');
});
Route::get('/paketwifi', [PaketWifiController::class, 'showPaketWifi'])->name('user.paketwifi');

Route::get('/tagihan', function () {
    return view('user.tagihan');
})->name('user.tagihan');

Route::get('/pengaduanwifi', function () {
    return view('user.pengaduanwifi');
})->name('user.pengaduanwifi');

// ===== USER TAGIHAN ROUTES =====
Route::get('/tagihan', [TagihanController::class, 'userTagihan'])->name('user.tagihan');
Route::get('/tagihan/show/{id}', [TagihanController::class, 'userTagihanShow'])->name('user.tagihan.show');
Route::get('/tagihan/history', [TagihanController::class, 'userTagihanHistory'])->name('user.tagihan.history');
Route::get('/tagihan/reminder', [TagihanController::class, 'userTagihanReminder'])->name('user.tagihan.reminder');
Route::get('/tagihan/invoice/{id}', [TagihanController::class, 'userTagihanDownloadInvoice'])->name('user.tagihan.invoice');
Route::get('/tagihan/payment', [UserTagihanController::class, 'payment']);
Route::post('/tagihan/create-transaction', [UserTagihanController::class, 'createTransaction']);
Route::post('/midtrans/callback', [UserTagihanController::class, 'handleCallback']);

// ===== TEKNISI ROUTES =====
Route::get('teknisi/dashboard', [TeknisiController::class, 'dashboard'])->middleware(['auth', 'role:teknisi'])->name('teknisi.dashboard');
Route::get('teknisi/pengajuan', [TeknisiController::class, 'pengajuan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengajuan.index');
Route::put('teknisi/pengajuan/{id}/update', [TeknisiController::class, 'updatePengajuan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengajuan.update');
Route::get('teknisi/pengajuan/{id}/edit', [TeknisiController::class, 'editPengajuan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengajuan.edit');
Route::delete('teknisi/pengajuan/{id}', [TeknisiController::class, 'destroyPengajuan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengajuan.destroy');
Route::get('teknisi/pengaduan', [TeknisiController::class, 'Pengaduan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengaduan.index');
Route::put('teknisi/pengaduan/{id}/update', [TeknisiController::class, 'updatePengaduan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengaduan.update');
Route::get('teknisi/pengaduan/{id}/edit', [TeknisiController::class, 'editPengaduan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengaduan.edit');
Route::delete('teknisi/pengaduan/{id}', [TeknisiController::class, 'destroyPengaduan'])->middleware(['auth', 'role:teknisi'])->name('teknisi.pengaduan.destroy');

// ===== PENGADUAN ROUTES =====
Route::get('pengaduan/create', [PengaduanController::class, 'create'])->name('dsahboard.pengaduan.create');
Route::post('pengaduan/store', [PengaduanController::class, 'store'])->name('dashboard.pengaduan.store');
Route::get('pengaduan', [PengaduanController::class, 'index'])->name('dashboard.pengaduan.index');
Route::get('pengaduan/edit/{id}', [PengaduanController::class, 'edit'])->name('dashboard.pengaduan.edit');
Route::put('pengaduan/update/{id}', [PengaduanController::class, 'update'])->name('dashboard.pengaduan.update');
Route::delete('pengaduan/destroy/{id}', [PengaduanController::class, 'destroy'])->name('dashboard.pengaduan.destroy');
Route::put('/pengaduan/{id}', [PengaduanController::class, 'update'])->name('dashboard.pengaduan.update');
Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy'])->name('dashboard.pengaduan.destroy');
Route::put('pengaduan/{id}/status', [PengaduanController::class, 'updateStatus'])->name('dashboard.pengaduan.updateStatus');
Route::get('/pengaduanwifi', [PengaduanController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduanwifi', [PengaduanController::class, 'store'])->name('pengaduan.store');

// ===== NOTIFICATION ROUTES =====
Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/mark_all_read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark_all_read');
Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->middleware('auth');
Route::post('/api/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->middleware('auth');
Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->middleware('auth');
Route::get('/notifications/', [NotificationController::class, 'getNotifications'])->name('notifications.get');
Route::get('/notifications/index', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
Route::get('/notifications/{id}/detail', [NotificationController::class, 'show']);
Route::delete('/notifications/{id}/delete', [NotificationController::class, 'destroy']);
Route::delete('/notifications/delete-all', [NotificationController::class, 'destroyAll']);
Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount']);

// ===== SUPERADMIN ROUTES =====
Route::get('superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->middleware(['auth', 'role:superadmin'])->name('superadmin.dashboard');

// SuperAdmin - DataPaket
Route::get('superadmin/datapaket', [PaketWifiController::class, 'datapaket'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapaket.index');
Route::get('superadmin/datapaket/create', [PaketWifiController::class, 'createSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapaket.create');
Route::post('superadmin/datapaket/store', [PaketWifiController::class, 'storeSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapaket.store');
Route::get('superadmin/datapaket/{id}/edit', [PaketWifiController::class, 'editSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapaket.edit');
Route::put('superadmin/datapaket/{id}/update', [PaketWifiController::class, 'updateSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapaket.update');
Route::delete('superadmin/datapaket/{id}', [PaketWifiController::class, 'destroySuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapaket.destroy');

// SuperAdmin - DataPelanggan
Route::get('superadmin/datapelanggan', [PelangganController::class, 'datapelanggan'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.index');
Route::get('superadmin/datapelanggan/create', [PelangganController::class, 'createSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.create');
Route::post('superadmin/datapelanggan/store', [PelangganController::class, 'storeSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.store');
Route::get('superadmin/datapelanggan/{id}/edit', [PelangganController::class, 'editSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.edit');
Route::get('superadmin/datapelanggan/{id}/show', [PelangganController::class, 'showSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.show');
Route::get('superadmin/datapelanggan/{id}/detail', [PelangganController::class, 'detailSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.detail');
Route::put('superadmin/datapelanggan/{id}/update', [PelangganController::class, 'updateSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.update');
Route::delete('superadmin/datapelanggan/{id}/', [PelangganController::class, 'destroySuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datapelanggan.destroy');
Route::patch('superadmin/pengajuan/{id}/selesai', [PengajuanController::class, 'selesai'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.selesai');

// SuperAdmin - DataTagihan
Route::get('superadmin/datatagihan', [TagihanController::class, 'datatagihanSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.index');
Route::get('superadmin/datatagihan/create', [TagihanController::class, 'createSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.create');
Route::post('superadmin/datatagihan/store', [TagihanController::class, 'storeSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.store');
Route::get('superadmin/datatagihan/{id}/show', [TagihanController::class, 'showSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.show');
Route::get('superadmin/datatagihan/{id}/edit', [TagihanController::class, 'editSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.edit');
Route::put('superadmin/datatagihan/{id}/update', [TagihanController::class, 'updateSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.update');
Route::delete('superadmin/datatagihan/{id}', [TagihanController::class, 'destroySuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.destroy');
Route::put('superadmin/updatestatus/{id_tagihan}', [TagihanController::class, 'updateStatusSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.updateStatus');

// SuperAdmin - Auto Tagihan
Route::post('superadmin/datatagihan/generate-bulanan', [TagihanController::class, 'generateTagihanBulanan'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.generate.bulanan');
Route::post('superadmin/datatagihan/generate-manual/{pengajuan_id}', [TagihanController::class, 'generateTagihanManual'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.generate.manual');
Route::get('superadmin/datatagihan/jatuh-tempo', [TagihanController::class, 'tagihanJatuhTempo'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.jatuh.tempo');
Route::get('superadmin/datatagihan/export-jatuh-tempo', [TagihanController::class, 'exportTagihanJatuhTempo'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.export.jatuh.tempo');
Route::get('superadmin/datatagihan/statistik', [TagihanController::class, 'statistikTagihan'])->middleware(['auth', 'role:superadmin'])->name('superadmin.datatagihan.statistik');

// SuperAdmin - Pengaduan
Route::get('superadmin/pengaduan', [PengaduanController::class, 'pengaduanSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengaduan.index');
Route::get('superadmin/pengaduan/create', [PengaduanController::class, 'createSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengaduan.create');
Route::post('superadmin/pengaduan/store', [PengaduanController::class, 'storeSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengaduan.store');
Route::get('superadmin/pengaduan/{id}/edit', [PengaduanController::class, 'editSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengaduan.edit');
Route::put('superadmin/pengaduan/{id}/update', [PengaduanController::class, 'updateSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengaduan.update');
Route::delete('superadmin/pengaduan/{id}', [PengaduanController::class, 'destroySuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengaduan.destroy');
Route::get('superadmin/pengaduan/{id}/show', [TagihanController::class, 'showSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengaduan.show');

// SuperAdmin - Pengajuan
Route::get('superadmin/pengajuan', [PengajuanController::class, 'pengajuanSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.index');
Route::get('superadmin/pengajuan/create', [PengajuanController::class, 'createSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.create');
Route::post('superadmin/pengajuan/store', [PengajuanController::class, 'storeSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.store');
Route::get('superadmin/pengajuan/{id}/detail', [PengajuanController::class, 'detailSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.detail');
Route::get('superadmin/pengajuan/{id}/edit', [PengajuanController::class, 'editSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.edit');
Route::put('superadmin/pengajuan/{id}', [PengajuanController::class, 'updateSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.update');
Route::delete('superadmin/pengajuan/{id}', [PengajuanController::class, 'destroySuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.destroy');
Route::get('superadmin/pengajuan/{id}/show', [TagihanController::class, 'showSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.show');
Route::put('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatusSuperAdmin'])->middleware(['auth', 'role:superadmin'])->name('superadmin.pengajuan.update');

// SuperAdmin - Laporan Keuangan
Route::get('/superadmin/laporankeuangan', [LaporanKeuanganController::class, 'index'])->middleware(['auth', 'role:superadmin'])->name('superadmin.laporankeuangan.index');

// SuperAdmin - API Routes
Route::get('api/superadmin/tagihan/data', [TagihanController::class, 'getDataTagihan'])->middleware(['auth', 'role:superadmin'])->name('api.superadmin.tagihan.data');
Route::get('api/superadmin/tagihan/statistik', [TagihanController::class, 'getStatistikTagihan'])->middleware(['auth', 'role:superadmin'])->name('api.superadmin.tagihan.statistik');

// ===== PELANGGAN TAGIHAN ROUTES =====
Route::get('pelanggan/tagihan', [TagihanController::class, 'tagihanPelanggan'])->middleware(['auth'])->name('pelanggan.tagihan.index');
Route::get('pelanggan/tagihan/{id}/detail', [TagihanController::class, 'detailTagihanPelanggan'])->middleware(['auth'])->name('pelanggan.tagihan.detail');
Route::get('pelanggan/tagihan/{id}/invoice', [TagihanController::class, 'downloadInvoice'])->middleware(['auth'])->name('pelanggan.tagihan.invoice');




use App\Http\Controllers\FinancialController;

Route::prefix('financial')->middleware(['auth'])->group(function () {
    // Dashboard page
    Route::get('/superadmin/laporankeuangan', [FinancialController::class, 'index'])->name('superadmin.laporankeuangan.index');
    
    // API endpoints for AJAX requests
    Route::get('/data', [FinancialController::class, 'getFinancialData'])->name('financial.data');
    Route::get('/transaction/{id}', [FinancialController::class, 'getTransaction'])->name('financial.transaction');
    Route::post('/transaction/{id}/mark-paid', [FinancialController::class, 'markAsPaid'])->name('financial.mark-paid');
    
    // Export endpoints
    Route::get('/export/excel', [FinancialController::class, 'exportExcel'])->name('financial.export.excel');
    Route::get('/export/pdf', [FinancialController::class, 'exportPDF'])->name('financial.export.pdf');
    // Route untuk data grafik
    Route::get('/financial/charts', [FinancialController::class, 'getChartData']);
    Route::get('/financial/charts/monthly-revenue', [FinancialController::class, 'getMonthlyRevenue']);
    Route::get('/financial/charts/status-distribution', [FinancialController::class, 'getStatusDistribution']);
    });



/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

// Halaman verifikasi email
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])
    ->middleware('auth')
    ->name('verification.notice'); // WAJIB pakai nama ini

// Proses verifikasi dari link email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify'); // WAJIB pakai nama ini

// Kirim ulang email verifikasi
Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
    ->middleware('auth')
    ->name('verification.send'); // WAJIB pakai nama ini

// Tambahan opsional (jika kamu ingin alias)

Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
    ->middleware('auth')
    ->name('auth.verify-resend');

Route::post('/email/change', [AuthController::class, 'changeEmail'])
    ->middleware('auth')
    ->name('auth.verify-change-email');



Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
    ->middleware('auth')
    ->name('verification.send'); // ✅ WAJIB nama ini!





/*
|--------------------------------------------------------------------------
| Protected Route After Verification
|--------------------------------------------------------------------------
*/



Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/superadmin/dashboard', [DashboardController::class, 'dashboard'])->name('superadmin.dashboard');

Route::get('/admin', [AdminController::class, 'dashboard'])->name('dashboard.admin');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])->name('dashboard.admin');
});


// Forgot Password Routes - dengan return view
Route::get('/forgot-password', function() {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes - dengan return view
Route::get('/reset-password/{token}', function($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


