<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Dosen - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .hero-gradient { background: linear-gradient(180deg, #A7C5E0 0%, #F8FAFC 100%); }
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="{ profileOpen: false }">

    {{-- NAVBAR: Diseragamkan Total dengan Halaman Antrean --}}
    <nav class="bg-[#2D3E50] text-white py-4 px-6 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">

            {{-- Sisi Kiri: Logo dan Nama Instansi --}}
            <div class="flex items-center space-x-5">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-9">

                    {{-- Garis Pembatas Vertikal setelah Logo --}}
                    <div class="h-8 w-[1px] bg-slate-500/40 mx-1"></div>

                    <div class="flex flex-col">
                        <span class="text-xs font-black uppercase tracking-wider leading-none">PERPUSTAKAAN</span>
                        <span class="text-[8px] text-yellow-400 font-bold uppercase tracking-wider mt-1">Institut Teknologi Bacharuddin Jusuf Habibie</span>
                    </div>
                </div>
            </div>

            {{-- Menu Tengah: Integrasi Menu Navigasi Dosen --}}
            <div class="hidden md:flex items-center space-x-8 text-[11px] font-bold uppercase tracking-widest">
                <a href="{{ route('dosen.beranda') }}" class="text-yellow-400 border-b-2 border-yellow-400 pb-1">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="text-slate-300 hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-journal-bookmark"></i> Katalog
                </a>
                <a href="{{ route('dosen.usulan.create') }}" class="text-slate-300 hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-journal-plus"></i> Usul Buku
                </a>
                @if(Auth::user()->role === 'kaprodi')
                <a href="{{ route('dosen.claim.index') }}" class="text-slate-300 hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-qr-code-scan"></i> Klaim Buku
                </a>
                @endif
            </div>

            {{-- Bagian Kanan: Lonceng Notifikasi & Pengguna Aktif --}}
            <div class="flex items-center gap-6">

                {{-- 1. Ikon Lonceng Notifikasi --}}
                <div class="relative">
                    <a href="{{ route('notifikasi.index') }}" class="relative inline-flex items-center p-1 text-white/80 hover:text-yellow-400 transition-all">
                        <i class="bi bi-bell text-xl"></i>

                        @php
                            $riwayatPinjamCollection = collect($riwayatPinjam ?? []);
                            $lateCount = $riwayatPinjamCollection->where('status', 'Dipinjam')->filter(function($item) {
                                return $item->tgl_kembali && \Carbon\Carbon::parse($item->tgl_kembali)->isPast();
                            })->count();
                        @endphp

                        @if($lateCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-[9px] text-white font-black items-center justify-center">
                                    {{ $lateCount }}
                                </span>
                            </span>
                        @endif
                    </a>
                </div>

                {{-- 2. Info Profil & Dropdown Kapsul --}}
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" class="flex items-center space-x-4 focus:outline-none group text-left">
                        <div class="flex flex-col text-right hidden sm:block">
                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Pengguna Aktif</span>
                            <span class="text-xs font-bold text-white tracking-wide leading-none group-hover:text-yellow-400 transition-colors">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="relative">
                            <i class="bi bi-person-circle text-2xl text-emerald-400 group-hover:scale-105 transition-transform"></i>
                            <span class="absolute -bottom-0.5 -right-0.5 block h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-[#2D3E50]"></span>
                        </div>
                    </button>

                    {{-- Dropdown Menu (Gaya Rounded Elegan) --}}
                    <div x-show="profileOpen"
                         @click.away="profileOpen = false"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 mt-3 w-64 bg-white rounded-[2rem] shadow-2xl border border-slate-100 py-4 z-[60] text-slate-700">

                        <div class="px-6 py-2 border-b border-slate-50 mb-2">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Status: Dosen Pengajar</p>
                            <p class="text-[9px] text-[#2D3E50] font-bold uppercase tracking-tight mt-0.5">NIP: {{ Auth::user()->nomor_identitas }}</p>
                        </div>

                        {{-- Menu: Edit Profil --}}
                        <a href="{{ route('profil.edit') }}" class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 hover:text-amber-600 transition-all group">
                            <i class="bi bi-person-gear text-amber-500 text-lg"></i>
                            <span class="text-[11px] font-extrabold uppercase">Edit Profil</span>
                        </a>

                        {{-- Menu: Riwayat Usulan --}}
                        <a href="{{ route('dosen.usulan.riwayat') }}" class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                            <i class="bi bi-clock-history text-indigo-500 text-lg"></i>
                            <span class="text-[11px] font-extrabold uppercase">Riwayat Usulan</span>
                        </a>

                        {{-- Menu: Antrean Reservasi --}}
                        <a href="{{ route('reservasi.index') }}" class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 hover:text-purple-600 transition-all group">
                            <i class="bi bi-calendar-check-fill text-purple-500 text-lg"></i>
                            <span class="text-[11px] font-extrabold uppercase">Antrean Reservasi</span>
                        </a>

                        {{-- Tombol Keluar Sistem --}}
                        <div class="border-t border-slate-100 mt-3 pt-3">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-4 px-6 py-3 text-rose-500 hover:bg-rose-50 transition-all group text-left">
                                    <i class="bi bi-box-arrow-right text-lg"></i>
                                    <span class="text-[11px] font-black uppercase tracking-widest">Keluar Sistem</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <header class="hero-gradient pt-12 pb-20 relative overflow-hidden">
        <div class="container mx-auto flex flex-col md:flex-row items-center px-6">
            <div class="md:w-1/2 z-10 text-center md:text-left">
                <h1 class="text-6xl font-extrabold text-[#1A2B3C] leading-none uppercase tracking-tighter">
                    PANEL <br> <span class="text-indigo-600 italic tracking-normal">DOSEN</span>
                </h1>
                <div class="h-1.5 w-20 bg-indigo-600 my-6 mx-auto md:mx-0"></div>
                <p class="text-slate-600 text-sm max-w-sm leading-relaxed italic mx-auto md:mx-0 font-medium">
                    Selamat datang kembali, Pak/Bu. Kelola usulan referensi dan pantau peminjaman buku untuk Prodi <strong>{{ Auth::user()->prodi }}</strong>.
                </p>
            </div>
            <div class="md:w-1/2 flex justify-end mt-10 md:mt-0">
                <img src="{{ asset('images/books.png') }}" alt="Hero" class="w-full max-w-md drop-shadow-2xl">
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="container mx-auto -mt-10 px-6 pb-20 relative z-20 space-y-20">

        {{-- SECTION: BUKU TERBARU --}}
        <section>
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tighter">Koleksi Terbaru</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Daftar buku yang baru saja masuk ke perpustakaan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($bukuTerbaru as $buku)
                <div class="group bg-[#1E293B] rounded-[2.5rem] p-5 shadow-2xl border border-white/5 flex flex-col h-full transition-all duration-500 hover:-translate-y-2">
                    <div class="aspect-[3/4] rounded-3xl bg-slate-800 mb-5 overflow-hidden relative shadow-inner">
                        <img src="{{ $buku->gambar_buku ? asset('images/' . $buku->gambar_buku) : asset('images/default-cover.jpg') }}"
                             class="w-full h-full object-cover opacity-90 group-hover:scale-110 group-hover:opacity-100 transition-all duration-700">
                        <div class="absolute top-3 left-3 bg-emerald-500 text-white text-[8px] font-black uppercase px-3 py-1 rounded-full shadow-lg">
                            New Arrival
                        </div>
                    </div>

                    <div class="px-2 flex-grow">
                        <h3 class="font-black text-white text-sm uppercase leading-tight mb-1 line-clamp-2 italic tracking-tight">
                            {{ $buku->judul }}
                        </h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mb-4">
                            By: {{ $buku->penulis ?? 'Anonim' }}
                        </p>
                    </div>

                    <div class="px-2 pb-2 mt-auto">
                        <a href="{{ route('buku.detail', $buku->id) }}" class="w-full bg-white/10 hover:bg-yellow-400 hover:text-[#1E293B] text-white py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all duration-300 border border-white/10">
                            <i class="bi bi-bookmark-plus text-sm"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Detail Koleksi</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest italic">Tidak ada buku terbaru yang ditambahkan.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- SECTION: KLAIM PRODI --}}
        <section>
            <div class="mb-10">
                <h2 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tighter">Koleksi Klaim Prodi</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Buku yang telah disetujui untuk {{ Auth::user()->prodi }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($bukuSaya as $item)
                <div class="bg-white rounded-[2.5rem] p-5 shadow-xl shadow-slate-200/50 border border-white flex flex-col h-full transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                    <div class="aspect-[3/4] rounded-3xl bg-slate-100 mb-5 overflow-hidden shadow-inner relative group">
                        <img src="{{ $item->buku && $item->buku->gambar_buku ? asset('images/' . $item->buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div class="px-2 flex-grow">
                        <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-1 line-clamp-2 italic">{{ $item->buku?->judul ?? 'Judul Tidak Tersedia' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-4">BY: {{ $item->buku?->penulis ?? 'ANONIM' }}</p>
                    </div>
                    <div class="px-2 pb-2 mt-auto">
                        <a href="{{ route('buku.detail', $item->buku->id ?? 0) }}" class="w-full bg-[#2D3E50] hover:bg-indigo-600 text-white py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all duration-300">
                            <i class="bi bi-eye text-sm"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Detail Buku</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest italic">Belum ada koleksi klaim prodi.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- SECTION: BUKU POPULER --}}
        <section>
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tighter">Buku Populer</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Referensi yang sering digunakan di ITH</p>
                </div>
                <a href="{{ route('katalog.index') }}" class="text-[10px] font-black text-indigo-600 uppercase border-b-2 border-indigo-600 pb-1">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($bukuPopuler ?? [] as $buku)
                <div class="bg-white rounded-[2.5rem] p-5 shadow-xl border border-white flex flex-col h-full transition-all hover:shadow-2xl">
                    <div class="aspect-[3/4] rounded-3xl bg-slate-100 mb-5 overflow-hidden relative">
                        <img src="{{ $buku->gambar_buku ? asset('images/' . $buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover">
                        <div class="absolute top-3 right-3 bg-rose-500 text-white text-[8px] font-black uppercase px-2 py-1 rounded-full shadow-lg">
                            <i class="bi bi-fire"></i> Populer
                        </div>
                    </div>
                    <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-1 line-clamp-2 px-2">{{ $buku->judul }}</h3>
                    <div class="px-2 mt-auto pt-4">
                        <a href="{{ route('buku.detail', $buku->id) }}" class="w-full bg-rose-500 hover:bg-rose-600 text-white py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all">
                            <span class="text-[10px] font-black uppercase tracking-widest">Detail Buku</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- SECTION: RIWAYAT PEMINJAMAN --}}
        <section class="mt-20">
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tighter">Riwayat Peminjaman Anda</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Daftar buku yang sedang atau telah dipinjam</p>
                </div>
                <div class="bg-indigo-50 px-4 py-2 rounded-2xl">
                    <span class="text-[10px] font-black text-indigo-600 uppercase italic">Total: {{ count($riwayatPinjam) }} Transaksi</span>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Info Buku</th>
                                <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Tanggal Pinjam</th>
                                <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Batas Kembali</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($riwayatPinjam as $pinjam)
                            <tr class="hover:bg-slate-50/80 transition-all duration-300 group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="h-16 w-12 bg-slate-200 rounded-xl overflow-hidden shadow-md group-hover:scale-110 transition-transform duration-300">
                                            <img src="{{ $pinjam->buku->gambar_buku ? asset('images/'.$pinjam->buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-[#2D3E50] leading-tight group-hover:text-indigo-600 transition-colors uppercase italic">{{ $pinjam->buku->judul }}</p>
                                            <p class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter mt-1">Penulis: {{ $pinjam->buku->penulis }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-calendar2-check text-indigo-500"></i>
                                        <span class="text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-calendar2-x text-rose-400"></i>
                                        <span class="text-xs font-bold text-slate-600">{{ $pinjam->tgl_kembali ? \Carbon\Carbon::parse($pinjam->tgl_kembali)->translatedFormat('d M Y') : '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($pinjam->status == 'Dipinjam')
                                        <span class="px-4 py-1.5 bg-amber-50 text-amber-600 text-[9px] font-black uppercase rounded-full border border-amber-200 shadow-sm">
                                            <i class="bi bi-clock-history mr-1"></i> Aktif
                                        </span>
                                    @elseif($pinjam->status == 'Kembali')
                                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-full border border-emerald-200 shadow-sm">
                                            <i class="bi bi-check2-circle mr-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 bg-rose-50 text-rose-600 text-[9px] font-black uppercase rounded-full border border-rose-200 shadow-sm">
                                            <i class="bi bi-exclamation-triangle mr-1"></i> Terlambat
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-folder2-open text-3xl text-slate-200"></i>
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] italic">Belum ada riwayat peminjaman ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="py-12 bg-white border-t border-slate-100">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.3em] mb-2">SIPUSTAKA DIGITAL LIBRARY</p>
            <p class="text-[8px] text-slate-400 font-bold uppercase italic">Institut Teknologi Bacharuddin Jusuf Habibie (ITH) &copy; 2026 • Parepare, Indonesia</p>
        </div>
    </footer>

</body>
</html>
