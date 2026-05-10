<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use App\Models\Eksemplar;
use App\Models\Setting;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan riwayat transaksi dengan filter status
     */
    public function index(Request $request)
    {
        $status = $request->get('status');

        $query = Peminjaman::with(['user', 'buku', 'eksemplar']);

        // Filter berdasarkan status (Dipinjam / Dikembalikan)
        if ($status) {
            $query->where('status', $status);
        }

        $transaksi = $query->latest()->paginate(10)->withQueryString();

        return view('shared.transaksi.index', compact('transaksi'));
    }

    /**
     * Menampilkan form peminjaman baru
     */
    public function create()
    {
        return view('shared.transaksi.create');
    }

    /**
     * Pencarian anggota via AJAX
     */
    public function getUsers(Request $request)
    {
        $keyword = $request->nim;

        $user = User::whereIn('role', ['mahasiswa', 'dosen', 'pustakawan', 'admin'])
            ->where(function($query) use ($keyword) {
                $query->where('nomor_identitas', $keyword)
                      ->orWhere('name', 'like', "%$keyword%");
            })
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Data anggota tidak ditemukan.']);
        }

        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'nomor_identitas' => $user->nomor_identitas,
            'role' => strtoupper($user->role),
            'status' => $user->status_akun == 'aktif' ? 'AKTIF' : 'NON-AKTIF'
        ]);
    }

    /**
 * Pencarian buku via AJAX untuk Select2
 */
public function getBooks(Request $request)
{
    // Select2 mengirim keyword pencarian lewat parameter 'q'
    $cari = $request->q;

    $books = Buku::where('judul', 'LIKE', "%$cari%")
        ->where('stok', '>', 0) // Hanya tampilkan yang stoknya ada
        ->get()
        ->map(function($item) {
            return [
                'id'    => $item->id,
                'text'  => $item->judul,
                'stok'  => $item->stok
            ];
        });

    return response()->json($books);
}

/**
 * Mengambil daftar nomor induk fisik (eksemplar) yang tersedia
 */
public function getEksemplar($buku_id)
{
    // Cari data eksemplar berdasarkan buku_id yang statusnya masih tersedia
    $eksemplar = \App\Models\Eksemplar::where('buku_id', $buku_id)
                ->where('status', 'tersedia') // Pastikan statusnya cocok dengan di database
                ->get();

    return response()->json($eksemplar);
}
    /**
     * Proses simpan peminjaman baru
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
                    'tgl_tenggat'  => $request->tgl_tenggat,
                    'status'       => 'dipinjam',
                ]);

                $eksemplar->update(['status' => 'dipinjam']);
                $this->syncStok($eksemplar->buku_id);

                $daftarJudul[] = $eksemplar->buku->judul;
            }

            $this->createWebNotif(
                $user->id,
                'Peminjaman Berhasil 📚',
                "Batas pengembalian: " . Carbon::parse($request->tgl_tenggat)->format('d/m/Y'),
                'info',
                'bi-book'
            );

            DB::commit();

            // Notifikasi WA
            if ((Setting::where('key', 'notif_borrow')->first()->value ?? '0') == '1') {
                $this->kirimNotifWA($user, 'borrow', $daftarJudul, $request->tgl_tenggat);
            }

            return redirect()->route('shared.transaksi.index')->with('success', 'Peminjaman berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Proses pengembalian buku
     */
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'kondisi_kembali' => 'required|in:baik,rusak,hilang', // Gunakan lowercase agar sinkron dengan match
            'denda_fisik'     => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::with(['user', 'buku'])->findOrFail($id);

            // 1. Update data peminjaman
            $peminjaman->update([
                'status'                => 'dikembalikan',
                'tgl_kembali'           => now(), // Gunakan tgl_kembali sesuai logika blade
                'kondisi_kembali'       => $request->kondisi_kembali,
                'denda_fisik'           => $request->denda_fisik ?? 0,
                'catatan_kondisi'       => $request->catatan_kondisi,
            ]);

            // 2. Update status fisik eksemplar
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

            DB::commit();

            // Notifikasi WA Return
            if ((Setting::where('key', 'notif_return')->first()->value ?? '0') == '1') {
                $this->kirimNotifWA($peminjaman, 'return', $request->kondisi_kembali);
            }

            return redirect()->route('shared.transaksi.index')->with('success', 'Buku berhasil dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Gagal: " . $e->getMessage());
        }
    }

    // --- Helper Methods ---

    private function syncStok($bukuId) {
        $count = Eksemplar::where('buku_id', $bukuId)->where('status', 'tersedia')->count();
        Buku::where('id', $bukuId)->update(['stok' => $count]);
    }

    private function createWebNotif($userId, $judul, $pesan, $tipe, $ikon) {
        Notification::create([
            'user_id' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => $tipe,
            'ikon'    => $ikon,
            'sudah_dibaca' => false,
        ]);
    }

    private function kirimNotifWA($model, $type, $extra = null, $extra2 = null) {
        $token = Setting::where('key', 'wa_token')->first()->value ?? env('FONNTE_TOKEN');
        if (!$token) return;

        if ($type === 'return') {
            $phone = $model->user->no_telp;
            $pesan = "✅ *PENGEMBALIAN BERHASIL*\n\nBuku: *{$model->buku->judul}*\nKondisi: " . strtoupper($extra);
        } else {
            $phone = $model->no_telp;
            $list = is_array($extra) ? implode("\n- ", $extra) : $extra;
            $pesan = "📚 *PEMINJAMAN BERHASIL*\n\nBuku:\n- {$list}\n\nBatas kembali: *" . Carbon::parse($extra2)->format('d-m-Y') . "*";
        }

        if ($phone) {
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
}