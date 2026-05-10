<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - SIPUSTAKA ITH</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F8FAFC; 
        }
        .line-clamp-1 { 
            display: -webkit-box; 
            -webkit-line-clamp: 1; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
        }
    </style>
</head>
<body class="antialiased">

    {{-- Header / Navigation --}}
    <nav class="bg-[#2D3E50] text-white px-8 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
        <div class="flex items-center gap-3">
            <a href="{{ route('mahasiswa.beranda') }}" class="flex items-center gap-2 group">
                <i class="bi bi-arrow-left-circle text-2xl text-yellow-400 group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Kembali ke Beranda</span>
            </a>
        </div>
        <div class="text-right leading-none">
            <p class="text-[10px] font-black uppercase text-emerald-400 italic">Riwayat Peminjaman</p>
            <p class="text-[8px] text-slate-400 font-bold uppercase mt-1 tracking-tighter">{{ Auth::user()->name }}</p>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-12">
        
        {{-- Judul Halaman --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tighter">Aktivitas Peminjaman</h1>
                <p class="text-xs text-slate-500 font-medium italic">Menampilkan bukti digital pengembalian buku dan riwayat denda fisik.</p>
            </div>
            <div class="text-right bg-rose-50 px-6 py-3 rounded-2xl border border-rose-100 shadow-sm">
                <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1">Total Akumulasi Denda</p>
                <p class="text-xl font-black text-rose-500">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel Riwayat Terpadu --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="bi bi-clock-history text-blue-600"></i>
                    <h2 class="text-sm font-black text-[#2D3E50] uppercase tracking-tighter">Daftar Aktivitas Buku</h2>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Data Terupdate: {{ date('d M Y') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-50">
                            <th class="px-8 py-5">Buku & Kondisi</th>
                            <th class="px-6 py-5">Tgl Pinjam</th>
                            <th class="px-6 py-5">Waktu Kembali (Sistem)</th>
                            <th class="px-6 py-5 text-center">Status</th>
                            <th class="px-6 py-5 text-right">Denda Fisik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($riwayat as $peminjaman)
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-16 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 shadow-sm border border-slate-200">
                                        <img src="{{ asset('images/' . $peminjaman->buku->gambar_buku) }}" 
                                             class="w-full h-full object-cover" 
                                             onerror="this.src='https://placehold.co/400x600?text=No+Cover'">
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-[#2D3E50] uppercase leading-tight line-clamp-1">{{ $peminjaman->buku->judul }}</p>
                                        
                                        {{-- Info Kondisi dari Admin --}}
                                        @if($peminjaman->status == 'dikembalikan' || $peminjaman->status == 'kembali')
                                            <p class="text-[9px] font-bold uppercase mt-1 {{ $peminjaman->kondisi_kembali == 'Baik' ? 'text-emerald-500' : 'text-rose-500' }}">
                                                <i class="bi {{ $peminjaman->kondisi_kembali == 'Baik' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} mr-1"></i>
                                                Kondisi: {{ $peminjaman->kondisi_kembali ?? 'Tidak Tercatat' }}
                                            </p>
                                        @else
                                            <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Sisa Pinjam: {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($peminjaman->tgl_kembali), false) }} Hari</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <p class="text-[11px] font-bold text-slate-600">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d M Y') }}</p>
                                <p class="text-[9px] text-slate-400 font-medium">Batas: {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->translatedFormat('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-6">
                                @if($peminjaman->tgl_realisasi_kembali)
                                    <p class="text-[11px] font-bold text-emerald-600">{{ \Carbon\Carbon::parse($peminjaman->tgl_realisasi_kembali)->translatedFormat('d M Y') }}</p>
                                    <p class="text-[9px] text-emerald-400 font-medium">Pukul {{ \Carbon\Carbon::parse($peminjaman->tgl_realisasi_kembali)->format('H:i') }} WITA</p>
                                @else
                                    <span class="text-[11px] font-bold text-slate-300 italic">Belum dikembalikan</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-center">
                                @php 
                                    $status = strtolower($peminjaman->status);
                                    $hariIni = \Carbon\Carbon::now();
                                    $batasKembali = \Carbon\Carbon::parse($peminjaman->tgl_kembali);
                                    $isTerlambat = ($status == 'dipinjam') && $hariIni->gt($batasKembali);
                                @endphp

                                @if($isTerlambat)
                                    <span class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-full text-[8px] font-black uppercase tracking-widest border border-rose-100 shadow-sm">Terlambat</span>
                                @elseif($status == 'dipinjam')
                                    <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[8px] font-black uppercase tracking-widest border border-blue-100 shadow-sm">Sedang Dipinjam</span>
                                @elseif($status == 'kembali' || $status == 'selesai' || $status == 'dikembalikan')
                                    <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[8px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm">Diterima Pustakawan</span>
                                @else
                                    <span class="px-3 py-1.5 bg-rose-600 text-white rounded-full text-[8px] font-black uppercase tracking-widest shadow-sm">{{ $status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-right">
                                <p class="text-[11px] font-black {{ ($peminjaman->denda_fisik ?? 0) > 0 ? 'text-rose-500' : 'text-slate-300' }}">
                                    Rp {{ number_format($peminjaman->denda_fisik ?? 0, 0, ',', '.') }}
                                </p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="opacity-20 mb-4 flex justify-center text-4xl"><i class="bi bi-folder-x"></i></div>
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada riwayat aktivitas.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Note Section --}}
        <div class="mt-6 px-8 py-4 bg-blue-50/50 rounded-2xl border border-blue-100">
            <p class="text-[10px] text-blue-600 font-medium">
                <i class="bi bi-info-circle-fill mr-2"></i>
                Halaman ini adalah bukti digital sah. Jika terdapat selisih data denda atau status buku, silakan hubungi petugas perpustakaan ITH dengan membawa ID Peminjaman.
            </p>
        </div>
    </main>

    <footer class="py-12 text-center border-t border-slate-100 bg-white">
        <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Sipustaka ITH &bull; Sistem Informasi Perpustakaan</p>
    </footer>

</body>
</html>