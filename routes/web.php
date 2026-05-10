<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, BukuController, KategoriController, PengumumanController,
    ProfilController, PeminjamanController, TransaksiController, UsulanController,
    KategoriAnggotaController, ClaimController, BukuTamuController, KatalogController,
    NotificationController, LaporanController
};
use App\Http\Controllers\Mahasiswa\BerandaController;
use App\Http\Controllers\Dosen\DosenController as PersonalDosenController;
use App\Http\Controllers\Admin\{
    AdminController, AnggotaController, MahasiswaController, DosenController,
    PustakawanController, PegawaiController, AdminTransaksiController,
    BukuController as AdminBukuController, WhatsappController as AdminWhatsappController
};
use App\Http\Controllers\Shared\{SettingController};
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReservationController;


/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/beranda', [LandingController::class, 'index'])->name('beranda');
Route::get('/buku/detail/{id}', [BukuController::class, 'showDetail'])->name('buku.detail');
// Katalog publik (halaman katalog terpisah)
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{id}', [KatalogController::class, 'showDetail'])->name('katalog.show');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Buku Tamu / Kunjungan
Route::get('/kunjungan', [BukuTamuController::class, 'create'])->name('buku-tamu.create');
Route::post('/kunjungan', [BukuTamuController::class, 'store'])->name('buku-tamu.store');


/*
|--------------------------------------------------------------------------
| 2. PROTECTED ROUTES (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- PROFIL, PEMINJAMAN UMUM & NOTIFIKASI (Dosen/Mahasiswa) ---
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/peminjaman/form/{id}', [PeminjamanController::class, 'showForm'])->name('pinjam.form');
    Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])->name('pinjam.ajukan');
    Route::get('/peminjaman/riwayat', [TransaksiController::class, 'riwayatPeminjaman'])->name('peminjaman.riwayat');

    // NOTIFIKASI (untuk semua user yang sudah login)
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifikasi.destroy');
    Route::delete('/notifikasi/all', [NotificationController::class, 'destroyAll'])->name('notifikasi.destroy-all');
    Route::get('/api/notifikasi/unread', [NotificationController::class, 'getUnreadCount'])->name('api.notifikasi.unread');

    Route::get('/reservasi', [ReservationController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/create/{buku_id}', [ReservationController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi/konfirmasi/{id}', [ReservationController::class, 'konfirmasi'])->name('reservasi.konfirmasi');

    // Route tunggal untuk semua role
    Route::post('/reservasi/store/{buku_id}', [App\Http\Controllers\ReservationController::class, 'store'])
         ->name('reservasi.store');
    
    Route::get('/reservasi', [App\Http\Controllers\ReservationController::class, 'index'])
         ->name('reservasi.index');
    
    Route::delete('/reservasi/{id}', [App\Http\Controllers\ReservationController::class, 'destroy'])
         ->name('reservasi.destroy');

    Route::get('/api/notifications/unread-count', function () {
        return response()->json(['count' => \App\Models\Notification::where('user_id', \Illuminate\Support\Facades\Auth::id())->where('sudah_dibaca', 0) ->count()
]);
    })->name('api.notifications.count');

    /*
    |--------------------------------------------------------------------------
    | A. SHARED ROUTES (ADMIN & PUSTAKAWAN)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['checkRole:admin,pustakawan'])->prefix('kelola')->name('shared.')->group(function() {

        // MANAJEMEN BUKU
        Route::resource('buku', AdminBukuController::class);
        Route::post('/buku/eksemplar/store', [AdminBukuController::class, 'storeEksemplar'])->name('eksemplar.store');
        Route::get('/buku/riwayat', [AdminTransaksiController::class, 'riwayat'])->name('buku.riwayat');
        Route::get('buku/klasifikasi/{kode}', [AdminBukuController::class, 'klasifikasi'])->name('buku.klasifikasi');
        Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{id}/update', [BukuController::class, 'update'])->name('buku.update');

        // MANAJEMEN PENGGUNA
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('dosen', \App\Http\Controllers\Admin\DosenController::class);

        Route::post('/dosen', [App\Http\Controllers\Admin\DosenController::class, 'store'])->name('dosen.store');
        Route::put('/dosen/{id}', [App\Http\Controllers\Admin\DosenController::class, 'update'])->name('dosen.update');

        Route::get('mahasiswa-export', [MahasiswaController::class, 'export'])->name('mahasiswa.export');
        Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
        Route::put('mahasiswa/{id}/reset-password', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset');
        Route::resource('pegawai', PegawaiController::class)->only(['index', 'store', 'destroy']);

        // KATEGORI
        Route::resource('kategori-buku', KategoriController::class);
        Route::resource('kategori-anggota', KategoriAnggotaController::class);

        // MANAJEMEN ANGGOTA
        Route::controller(AnggotaController::class)->group(function() {
            Route::get('anggota', 'index')->name('anggota.index');
            Route::delete('anggota/{id}', 'destroy')->name('anggota.destroy');
            Route::get('anggota/aktivasi', 'aktivasi')->name('anggota.aktivasi');
            Route::post('anggota/proses-aktivasi', 'store')->name('anggota.proses-aktivasi');
            Route::get('anggota/perpanjangan', 'perpanjangan')->name('anggota.perpanjangan');
            Route::post('anggota/update-status/{id}', 'updateStatus')->name('anggota.update-status');
        });

        // SIRKULASI & TRANSAKSI
        Route::controller(AdminTransaksiController::class)->group(function() {
        Route::get('/transaksi', 'index')->name('transaksi.index');
        // Pastikan ini mengarah ke method 'kembalikan' di AdminTransaksiController
        Route::post('/transaksi/kembalikan/{id}', 'kembalikan')->name('transaksi.kembalikan');
        });

        // PEMINJAMAN
        Route::controller(PeminjamanController::class)->group(function() {
            Route::get('/peminjaman', 'index')->name('peminjaman.index');
            Route::get('/peminjaman/tambah', 'create')->name('peminjaman.create');
            Route::get('/peminjaman/konfirmasi', 'konfirmasi')->name('peminjaman.konfirmasi');
            Route::post('/peminjaman/store', 'store')->name('peminjaman.store');
            Route::post('/peminjaman/kembali/{id}', 'kembalikan')->name('peminjaman.kembali');
            Route::patch('/transaksi/{id}/extend', 'extend')->name('peminjaman.extend');
            Route::get('/api/users-search', 'getUsers')->name('getUsers');
            Route::get('/api/books-search', 'getBooks')->name('api.books.search');
            Route::get('/api/get-eksemplar/{buku_id}', 'getEksemplar')->name('getEksemplar');
});

        // PERSETUJUAN & KONFIRMASI
        Route::post('/peminjaman/{id}/approve', [AdminBukuController::class, 'approvePeminjaman'])->name('peminjaman.approve');
        Route::post('/peminjaman/{id}/reject', [AdminBukuController::class, 'rejectPeminjaman'])->name('peminjaman.reject');

        // 1. Route untuk melihat DAFTAR SEMUA usulan (Link Sidebar)
        Route::get('/usulan-buku/konfirmasi', [UsulanController::class, 'index'])->name('usulan.konfirmasi');


        // 2. Route untuk PROSES update status (Tombol Setujui/Tolak)
        Route::patch('/usulan-buku/konfirmasi/{id}', [UsulanController::class, 'updateStatus'])->name('usulan.konfirmasi.update');

        Route::get('/usulan-buku', [UsulanController::class, 'index'])->name('usulan.index');


        // MANAJEMEN KLAIM BUKU
        Route::get('/konfirmasi-klaim', [ClaimController::class, 'indexKonfirmasi'])->name('claim.konfirmasi');
        Route::post('/klaim/setujui/{id}', [ClaimController::class, 'approve'])->name('claim.approve');

        // BUKU TAMU
        Route::get('/buku-tamu', [BukuTamuController::class, 'index'])->name('buku-tamu.index');

        // LAPORAN (Shared antara Admin & Pustakawan)
        Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
        // Pastikan ada bagian ->name(...) di ujungnya
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export_pdf');

       Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
       Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
       // Pastikan namanya persis dengan yang dipanggil di Blade
        Route::get('/settings/backup', [SettingController::class, 'backup'])->name('backup.download');
       Route::get('/reservasi', [ReservationController::class, 'index'])->name('reservasi.index');

       Route::get('/kelola-reservasi', [ReservationController::class, 'indexAdmin'])->name('admin.reservasi.index');
       Route::post('/kelola-reservasi/{id}/konfirmasi', [ReservationController::class, 'konfirmasi'])->name('admin.reservasi.konfirmasi');

    });

    /*
    |--------------------------------------------------------------------------
    | B. KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['checkRole:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('pustakawan', PustakawanController::class)->except(['show', 'edit', 'update']);
        Route::get('/whatsapp', [AdminWhatsappController::class, 'index'])->name('whatsapp.index');
        Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::get('/denda', [AdminController::class, 'indexDenda'])->name('denda.index');
        Route::get('/admin/daftar-buku', [BukuController::class, 'indexBukuAdmin'])->name('admin.daftar_buku');
    });

    /*
    |--------------------------------------------------------------------------
    | C. KHUSUS PUSTAKAWAN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['checkRole:pustakawan'])->prefix('pustakawan')->name('pustakawan.')->group(function () {
        Route::get('/dashboard', [AdminBukuController::class, 'pustakawanDashboard'])->name('dashboard');
        Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    });

    /*
    |--------------------------------------------------------------------------
    | D. ROLE MAHASISWA
    |--------------------------------------------------------------------------
    */
    Route::middleware(['checkRole:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
        Route::get('/pinjaman', [BerandaController::class, 'pinjamanAktif'])->name('pinjaman');
        Route::get('/riwayat', [BerandaController::class, 'riwayat'])->name('riwayat'); // Rute untuk "Riwayat"
        Route::get('/rekomendasi', [BerandaController::class, 'rekomendasiFull'])->name('rekomendasi.index');
        Route::get('/rekomendasi-lengkap', [BerandaController::class, 'rekomendasiFull'])->name('rekomendasi.full');
        Route::post('/pinjam/store/{id}', [PeminjamanController::class, 'store'])->name('pinjam.store');

        // FITUR USULAN BUKU MAHASISWA
        Route::get('/usulan-buku/baru', [UsulanController::class, 'create'])->name('usulan.create');
        Route::post('/usulan/store', [UsulanController::class, 'store'])->name('usulan.store');
        Route::get('/riwayat-usulan', [UsulanController::class, 'index'])->name('usulan.riwayat');

        Route::post('/reservasi/store/{buku_id}', [ReservationController::class, 'store'])->name('reservasi.store');    });

    /*
    |--------------------------------------------------------------------------
    | E. ROLE DOSEN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['checkRole:dosen,kaprodi'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/beranda', [PersonalDosenController::class, 'index'])->name('beranda');
    Route::get('/buku/{id}', [PersonalDosenController::class, 'showBuku'])->name('buku.show');

    // FITUR USULAN BUKU DOSEN
    Route::get('/usulan-buku/baru', [UsulanController::class, 'create'])->name('usulan.create');
    Route::post('/usulan/store', [UsulanController::class, 'store'])->name('usulan.store');
    
    // PERBAIKAN: Cukup tulis 'usulan.riwayat', nanti otomatis jadi 'dosen.usulan.riwayat'
    Route::get('/riwayat-usulan', [UsulanController::class, 'index'])->name('usulan.riwayat');

    // FITUR KLAIM BUKU DOSEN
    Route::get('/klaim-buku', [ClaimController::class, 'index'])->name('claim.index');
    Route::post('/klaim-buku/toggle', [ClaimController::class, 'toggle'])->name('claim.toggle');
    Route::get('/riwayat-klaim', [ClaimController::class, 'riwayat'])->name('klaim.riwayat');
});

});
