<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use App\Models\Eksemplar;
use App\Models\Setting; // Pastikan Model Setting diimport
use Carbon\Carbon;
use App\Traits\WhatsappTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    use WhatsappTrait;

    /**
     * Menampilkan daftar transaksi
     */
    public function index(Request $request)
    {
        $transaksi = Peminjaman::with(['user', 'buku'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', strtolower($status));
            })
            ->latest()
            ->get();

        return view('shared.transaksi.index', compact('transaksi'));
    }

    /**
     * PROSES PENGEMBALIAN (Otomatis Update Stok & Eksemplar + Notifikasi)
     */
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'kondisi_kembali' => 'required|in:Baik,Rusak,Hilang',
            'denda_fisik'     => 'nullable|numeric|min:0',
            'catatan_kondisi' => 'nullable|string'
        ]);

        try {
            // Ambil data peminjaman beserta relasi user dan buku
            $peminjaman = Peminjaman::with(['user', 'buku'])->findOrFail($id);

            DB::transaction(function () use ($request, $peminjaman) {
                // 1. Update Status Transaksi Peminjaman
                $peminjaman->update([
                    'status'           => 'dikembalikan',
                    'tgl_kembali'      => Carbon::now(),
                    'kondisi_kembali'  => $request->kondisi_kembali,
                    'denda_fisik'      => $request->denda_fisik ?? 0,
                    'catatan_kondisi'  => $request->catatan_kondisi,
                ]);

                // 2. Cari data Eksemplar
                $eksemplar = Eksemplar::find($peminjaman->eksemplar_id);
                if (!$eksemplar) {
                    $noIndukClean = trim($peminjaman->no_induk);
                    $eksemplar = Eksemplar::where('no_induk', 'LIKE', "%$noIndukClean%")->first();
                }

                // 3. Jika Eksemplar ditemukan, update status fisiknya
                if ($eksemplar) {
                    $statusFisik = ($request->kondisi_kembali == 'hilang') ? 'hilang' : 
                                  (($request->kondisi_kembali == 'rusak') ? 'rusak' : 'tersedia');
                    
                    $eksemplar->update(['status' => $statusFisik]);

                    // 4. Sinkronisasi Stok Katalog
                    $buku = $eksemplar->buku;
                    if ($buku) {
                        $jumlahTersedia = Eksemplar::where('buku_id', $buku->id)
                                                   ->where('status', 'tersedia')
                                                   ->count();
                        $buku->update(['stok' => $jumlahTersedia]);
                    }
                }
            });

            // --- LOGIKA NOTIFIKASI WHATSAPP ---
            $notifSetting = Setting::where('key', 'notif_return')->first();
            $phone = $peminjaman->user->no_hp ?? $peminjaman->user->no_telp;

            if ($notifSetting && $notifSetting->value == '1' && $phone) {
                $namaPerpus = Setting::where('key', 'nama_perpus')->first()->value ?? 'SIPUSTAKA';
                
                $pesan = "✅ *PENGEMBALIAN BUKU BERHASIL*\n\n" .
                         "Halo *{$peminjaman->user->name}*,\n" .
                         "Buku berikut telah diterima kembali:\n\n" .
                         "📖 Judul: *{$peminjaman->buku->judul}*\n" .
                         "📅 Tgl Kembali: " . Carbon::now()->format('d-m-Y H:i') . "\n" .
                         "🛡️ Kondisi: " . ucfirst($request->kondisi_kembali) . "\n" .
                         "💰 Denda Fisik: Rp " . number_format($request->denda_fisik ?? 0, 0, ',', '.') . "\n\n" .
                         "Terima kasih telah mengembalikan buku.\n" .
                         "-- {$namaPerpus}";

                $this->kirimPesanWA($phone, $pesan);
            }

            return redirect()->route('shared.transaksi.index')->with('success', 'Buku telah kembali dan notifikasi berhasil dikirim!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * SIMPAN PEMINJAMAN BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'buku_id'     => 'required|array|min:1',
            'no_induk_id' => 'required|array|min:1',
            'tgl_kembali' => 'required|date|after_or_equal:today',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $daftarBuku = [];

            DB::transaction(function () use ($request, &$daftarBuku) {
                foreach ($request->buku_id as $key => $idBuku) {
                    $eksemplar = Eksemplar::findOrFail($request->no_induk_id[$key]);

                    if (strtolower($eksemplar->status) !== 'tersedia') {
                        throw new \Exception("Eksemplar '$eksemplar->no_induk' tidak tersedia.");
                    }

                    Peminjaman::create([
                        'user_id'      => $request->user_id,
                        'buku_id'      => $idBuku,
                        'eksemplar_id' => $eksemplar->id,
                        'no_induk'     => trim($eksemplar->no_induk),
                        'tgl_pinjam'   => Carbon::now(),
                        'tgl_kembali'  => $request->tgl_kembali,
                        'status'       => 'dipinjam',
                    ]);

                    $eksemplar->update(['status' => 'dipinjam']);
                    
                    $buku = $eksemplar->buku;
                    $stokBaru = Eksemplar::where('buku_id', $buku->id)->where('status', 'tersedia')->count();
                    $buku->update(['stok' => $stokBaru]);

                    $daftarBuku[] = $buku->judul;
                }
            });

            // Notifikasi WhatsApp Pinjam Baru
            $phone = $user->no_hp ?? $user->no_telp;
            if ($phone) {
                $listBuku = "";
                foreach($daftarBuku as $index => $judul) {
                    $listBuku .= ($index+1) . ". " . $judul . "\n";
                }
                
                $pesan = "🔔 *NOTIFIKASI PINJAM*\n\n" .
                         "Halo *{$user->name}*,\n" .
                         "Peminjaman buku berhasil:\n\n" . 
                         $listBuku . 
                         "\n📅 *Batas Kembali:* " . Carbon::parse($request->tgl_kembali)->format('d-m-Y') . "\n" .
                         "Mohon jaga buku dengan baik.";
                
                $this->kirimPesanWA($phone, $pesan);
            }

            return redirect()->route('shared.transaksi.index')->with('success', 'Peminjaman berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * PERPANJANG MASA PINJAM
     */
    public function extend($id)
    {
        $transaksi = Peminjaman::findOrFail($id);
        $deadline = Carbon::parse($transaksi->tgl_kembali);

        if ($transaksi->is_extended || Carbon::now()->gt($deadline)) {
            return back()->with('error', 'Perpanjangan tidak diizinkan.');
        }

        $transaksi->update([
            'tgl_kembali' => $deadline->addDays(7),
            'is_extended' => true
        ]);

        return back()->with('success', 'Masa pinjam diperpanjang 7 hari.');
    }

    /**
     * API: Pencarian User
     */
    public function getUsers(Request $request)
    {
        $keyword = $request->nim;
        $user = User::where('nomor_identitas', $keyword)
            ->orWhere('name', 'like', "%$keyword%")
            ->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'id' => $user->id,
                'name' => $user->name,
                'nomor_identitas' => $user->nomor_identitas,
                'status' => '🟢 ' . strtoupper($user->status ?? 'AKTIF'),
            ]);
        }
        return response()->json(['success' => false]);
    }

    /**
     * API: Pencarian Buku & Stok
     */
    public function getBooks(Request $request)
    {
        $search = $request->search;
        $books = Buku::where('judul', 'like', "%$search%")->get();

        $data = $books->map(function($item) {
            $stokTersedia = Eksemplar::where('buku_id', $item->id)
                                     ->where('status', 'tersedia')
                                     ->count();
            return [
                'id'    => $item->id,
                'text'  => $item->judul,
                'stok'  => $stokTersedia
            ];
        })->filter(function($item) {
            return $item['stok'] > 0;
        })->values();

        return response()->json($data);
    }

    public function getEksemplar($buku_id)
    {
        return response()->json(Eksemplar::where('buku_id', $buku_id)->where('status', 'tersedia')->get(['id', 'no_induk']));
    }

    public function create()
    {
        return view('shared.transaksi.create');
    }

    /**
     * Fungsi Kirim WhatsApp (Mengambil Token dari Settings Database)
     */
    private function kirimPesanWA($target, $pesan)
    {
        // Ambil token dari tabel settings agar sinkron dengan menu Pengaturan
        $tokenSetting = Setting::where('key', 'wa_token')->first();
        $token = $tokenSetting ? $tokenSetting->value : env('FONNTE_TOKEN');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan,
                'countryCode' => '62', 
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}