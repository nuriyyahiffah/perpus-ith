<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use App\Models\Eksemplar;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * TAMPILAN DAFTAR TRANSAKSI
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = Peminjaman::with(['user', 'buku', 'eksemplar']);

        if ($status) {
            $query->where('status', $status);
        }

        $transaksi = $query->latest()->paginate(10)->withQueryString();
        return view('shared.transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        return view('shared.transaksi.create');
    }

    /**
     * CARI USER BERDASARKAN NIM/NAMA (AJAX)
     */
    public function getUsers(Request $request)
    {
        $keyword = $request->nim;
        $user = User::where('nomor_identitas', $keyword)
                    ->orWhere('name', 'like', "%$keyword%")
                    ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        $suspendedUntil = $user->is_suspended_until;
        if ($suspendedUntil && Carbon::now()->greaterThan($suspendedUntil)) {
            $suspendedUntil = null;
        }

        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'nomor_identitas' => $user->nomor_identitas,
            'role' => strtoupper($user->role),
            'status' => $suspendedUntil ? 'SUSPENDED' : 'AKTIF',
            'is_suspended_until' => $suspendedUntil,
            'is_suspended_until_formatted' => $suspendedUntil ? Carbon::parse($suspendedUntil)->format('d/m/Y') : null,
        ]);
    }

    /**
     * CARI BUKU (AJAX)
     */
    public function getBooks(Request $request)
    {
        $cari = $request->q;
        return response()->json(
            Buku::where('judul', 'LIKE', "%$cari%")
                ->where('stok', '>', 0)
                ->get()
                ->map(fn($item) => ['id' => $item->id, 'text' => $item->judul, 'stok' => $item->stok])
        );
    }

    /**
     * AMBIL DAFTAR EKSEMPLAR TERSEDIA
     */
    public function getEksemplar($buku_id)
    {
        return response()->json(
            Eksemplar::where('buku_id', $buku_id)->where('status', 'tersedia')->get()
        );
    }

    /**
     * PROSES SIMPAN PEMINJAMAN (BORROW)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'no_induk_id' => 'required|array|min:1',
            'tgl_tenggat' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);

            if ($user->is_suspended_until && Carbon::now()->lessThan($user->is_suspended_until)) {
                throw new \Exception("Gagal: Akun ini masih dalam masa suspensi.");
            }

            $tglTenggat = Carbon::parse($request->tgl_tenggat)->startOfDay()->addHours(12);
            $daftarJudul = [];

            foreach ($request->no_induk_id as $eksemplarId) {
                $eksemplar = Eksemplar::with('buku')->findOrFail($eksemplarId);

                if ($eksemplar->status !== 'tersedia') {
                    throw new \Exception("Buku '{$eksemplar->buku->judul}' tidak tersedia.");
                }

                Peminjaman::create([
                    'user_id'      => $user->id,
                    'buku_id'      => $eksemplar->buku_id,
                    'eksemplar_id' => $eksemplar->id,
                    'tgl_pinjam'   => Carbon::now(),
                    'tgl_tenggat'  => $tglTenggat,
                    'status'       => 'dipinjam',
                    'wa_sent'      => 0,
                ]);

                $eksemplar->update(['status' => 'dipinjam']);
                $this->syncStok($eksemplar->buku_id);
                $daftarJudul[] = $eksemplar->buku->judul;
            }

            $this->createWebNotif($user->id, 'Peminjaman Berhasil 📚', "Batas kembali: " . $tglTenggat->format('d/m/Y') . " jam 12:00", 'info', 'bi-book');

            DB::commit();

            $isNotifActive = Setting::where('key', 'notif_borrow')->first()->value ?? '0';
            if ($isNotifActive == '1') {
                $this->kirimNotifWA($user, 'borrow', $daftarJudul, $tglTenggat);
            }

            return redirect()->route('shared.transaksi.index')->with('success', 'Peminjaman berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * PROSES PENGEMBALIAN BUKU (RETURN) + LOGIKA RESERVASI OTOMATIS
     */
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'kondisi_kembali' => 'required|in:baik,rusak,hilang',
            'denda_fisik'     => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::with(['user', 'buku'])->findOrFail($id);
            $user = $peminjaman->user;
            $tglKembali = now();
            $tglTenggat = Carbon::parse($peminjaman->tgl_tenggat);

            // Logika Suspensi jika Terlambat
            if ($tglKembali->greaterThan($tglTenggat)) {
                $hariTerlambat = $tglKembali->diffInDays($tglTenggat);
                if ($hariTerlambat == 0) $hariTerlambat = 1;

                $durasi = match (true) {
                    $hariTerlambat <= 7  => 7,
                    $hariTerlambat <= 14 => 14,
                    $hariTerlambat <= 21 => 30,
                    default              => 90,
                };

                $user->update(['is_suspended_until' => now()->addDays($durasi)]);
            }

            // Update status peminjaman
            $peminjaman->update([
                'status'          => 'dikembalikan',
                'tgl_kembali'     => $tglKembali,
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda_fisik'     => $request->denda_fisik ?? 0,
            ]);

            // Update status eksemplar
            $eksemplar = Eksemplar::find($peminjaman->eksemplar_id);
            if ($eksemplar) {
                $statusFisik = match ($request->kondisi_kembali) {
                    'hilang' => 'hilang',
                    'rusak'  => 'rusak',
                    default  => 'tersedia',
                };
                $eksemplar->update(['status' => $statusFisik]);
                $this->syncStok($eksemplar->buku_id);
            }

            /**
             * LOGIKA OTOMATISASI RESERVASI
             * Hanya dipicu jika buku kembali dalam kondisi 'baik'
             */
            if ($request->kondisi_kembali === 'baik') {
                $antrean = Reservation::with(['user', 'buku'])
                            ->where('buku_id', $peminjaman->buku_id)
                            ->where('status', 'menunggu')
                            ->orderBy('created_at', 'asc')
                            ->first();

                if ($antrean) {
                    $antrean->update(['status' => 'tersedia']);

                    // Notif Web
                    $this->createWebNotif(
                        $antrean->user_id,
                        'Buku Siap Diambil 📚',
                        "Buku '{$antrean->buku->judul}' yang Anda reservasi sudah tersedia.",
                        'success',
                        'bi-check-circle'
                    );

                    // Notif WA Reservasi
                    $isNotifReserActive = Setting::where('key', 'notif_reservation')->first()->value ?? '0';
                    if ($isNotifReserActive == '1') {
                        $this->kirimNotifWA($antrean, 'reservation');
                    }
                }
            }

            DB::commit();
            return redirect()->route('shared.transaksi.index')->with('success', 'Buku dikembalikan. Status reservasi diperbarui & notifikasi terkirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function syncStok($bukuId) {
        $count = Eksemplar::where('buku_id', $bukuId)->where('status', 'tersedia')->count();
        Buku::where('id', $bukuId)->update(['stok' => $count]);
    }

    private function createWebNotif($userId, $judul, $pesan, $tipe, $ikon) {
        Notification::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'ikon' => $ikon,
            'sudah_dibaca' => false
        ]);
    }

    /**
     * FUNGSI UNIFIED KIRIM WA (Fonnte)
     */
    private function kirimNotifWA($model, $type, $extra = null, $extra2 = null) {
        $token = Setting::where('key', 'wa_token')->first()->value ?? env('FONNTE_TOKEN');
        if (!$token) return;

        $phone = "";
        $pesan = "";

        if ($type === 'borrow') {
            // $model = User
            $phone = $model->no_telp;
            $list = is_array($extra) ? implode("\n- ", $extra) : $extra;
            $pesan = "📚 *PEMINJAMAN BERHASIL*\n\nHalo *{$model->name}*,\nBuku:\n- {$list}\n\nBatas kembali: *" . Carbon::parse($extra2)->format('d-m-Y') . "*\n\nSimpan buku dengan baik ya!";
        }
        elseif ($type === 'reservation') {
            // $model = Reservation
            $phone = $model->user->no_telp;
            $pesan = "🔔 *BUKU RESERVASI TERSEDIA*\n\nHalo *{$model->user->name}*,\n\nBuku yang Anda antrekan:\n📚 *{$model->buku->judul}*\n\nSudah tersedia di Perpustakaan ITH. Silakan datang ke bagian sirkulasi untuk meminjam. Slot tersedia terbatas.";
        }

        if (empty($phone)) return;

        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => ['target' => $phone, 'message' => $pesan],
            CURLOPT_HTTPHEADER => ['Authorization: ' . $token],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($curl);
        curl_close($curl);
    }
}
