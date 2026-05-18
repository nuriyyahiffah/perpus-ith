<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku->judul }} - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased text-slate-800">

    {{-- NAVBAR SERAGAM SESUAI REFERENSI DESAIN --}}
    <nav class="bg-[#2D3E50] text-white py-4 px-6 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">

            {{-- Sisi Kiri: Tombol Kembali Pintar, Logo, dan Nama Instansi --}}
            <div class="flex items-center space-x-5">

                @php
                    $backUrl = url()->previous();
                    if($backUrl == url()->current()) {
                        $backUrl = match(Auth::user()->role) {
                            'admin' => route('sidebar-admin'),
                            'mahasiswa' => route('mahasiswa.beranda'),
                            'dosen', 'kaprodi' => route('dosen.beranda'),
                            'pustakawan' => route('sidebar-pustakawan'),
                            default => url('/'),
                        };
                    }
                @endphp

                {{-- Tombol Kembali Fleksibel & Aman --}}
                <a href="{{ $backUrl }}" class="text-white hover:text-slate-300 transition text-xl flex items-center" title="Kembali">
                    <i class="bi bi-arrow-left text-2xl font-bold"></i>
                </a>

                {{-- Garis Pembatas Vertikal Pertama --}}
                <div class="h-8 w-[1px] bg-slate-500/40"></div>

                {{-- Logo dan Teks Instansi Perpustakaan --}}
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-9">

                    {{-- Garis Pembatas Vertikal Kedua --}}
                    <div class="h-8 w-[1px] bg-slate-500/40 mx-1"></div>

                    <div class="flex flex-col">
                        <span class="text-xs font-black uppercase tracking-wider leading-none">PERPUSTAKAAN</span>
                        <span class="text-[8px] text-yellow-400 font-bold uppercase tracking-wider mt-1">Institut Teknologi Bacharuddin Jusuf Habibie</span>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Informasi Pengguna Aktif --}}
            <div class="flex flex-col text-right">
                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Pengguna Aktif</span>
                <span class="text-xs font-bold text-white tracking-wide leading-none">{{ Auth::user()->name }}</span>
            </div>

        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-6xl mx-auto">

            {{-- AREA NOTIFIKASI --}}
            <div class="mb-8 max-w-4xl mx-auto">
                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill text-rose-500 text-xl"></i>
                        <span class="text-sm font-bold">{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-50 flex flex-col md:flex-row">

                {{-- BAGIAN KIRI: COVER --}}
                <div class="md:w-[40%] bg-slate-50 p-12 flex flex-col items-center justify-center border-r border-slate-100">
                    <div class="relative group">
                        @if($buku->gambar_buku)
                            <img src="{{ asset('images/' . $buku->gambar_buku) }}" alt="{{ $buku->judul }}"
                                 class="w-64 shadow-[0_20px_50px_rgba(0,0,0,0.2)] rounded-2xl transform group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-64 aspect-[3/4] bg-slate-200 rounded-2xl flex flex-col items-center justify-center text-slate-400 border-2 border-dashed border-slate-300">
                                <i class="bi bi-book text-6xl mb-4"></i>
                                <span class="text-[10px] font-bold uppercase">Sampul Tidak Tersedia</span>
                            </div>
                        @endif
                        <div class="absolute -bottom-4 -right-4 bg-yellow-400 text-[#2D3E50] w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg font-black text-xs">
                            {{ $buku->tahun_terbit ?? '2026' }}
                        </div>
                    </div>
                </div>

                {{-- BAGIAN KANAN: DETAIL --}}
                <div class="md:w-[60%] p-10 md:p-16">
                    <div class="mb-8">
                        <span class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                            {{ $buku->kategori->nama_kategori ?? 'Koleksi Umum' }}
                        </span>
                        <h1 class="text-4xl font-black text-[#2D3E50] uppercase tracking-tight mt-6 leading-tight">
                            {{ $buku->judul }}
                        </h1>
                        <p class="text-slate-400 text-sm font-bold mt-3 flex items-center gap-2 uppercase tracking-widest">
                            <i class="bi bi-person-fill text-yellow-500"></i> {{ $buku->penulis }}
                        </p>
                    </div>

                    {{-- INFO GRID --}}
                    <div class="grid grid-cols-2 gap-6 py-6 border-y border-slate-100 mb-8">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Penerbit</h4>
                            <p class="text-sm font-bold text-[#2D3E50]">{{ $buku->penerbit ?? 'ITH Collection' }}</p>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ketersediaan</h4>
                            <p class="text-sm font-bold {{ ($buku->stok ?? 0) > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                @if(($buku->stok ?? 0) > 0)
                                    <i class="bi bi-check-circle-fill mr-1"></i> {{ $buku->stok }} Eksemplar
                                @else
                                    <i class="bi bi-x-circle-fill mr-1"></i> Stok Kosong
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- SINOPSIS --}}
                    <div class="mb-10">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Sinopsis Buku</h4>
                        <p class="text-slate-600 leading-relaxed text-sm italic">
                            "{{ $buku->sinopsis ?? 'Belum ada sinopsis untuk buku ini.' }}"
                        </p>
                    </div>

                    {{-- AREA TOMBOL AKSI --}}
                    <div class="flex flex-col gap-4">
                        @php $userRole = Auth::user()->role; @endphp

                        {{-- USER BIASA (Mahasiswa/Dosen/Kaprodi) --}}
                        @if(in_array($userRole, ['mahasiswa', 'dosen', 'kaprodi']))

                            @if(($buku->stok ?? 0) > 0)
                                {{-- JIKA STOK ADA --}}
                                <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-4">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-100">
                                        <i class="bi bi-info-circle"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-wider">Buku Tersedia</p>
                                        <p class="text-[11px] text-emerald-700 font-medium">Silakan kunjungi perpustakaan ITH untuk peminjaman langsung.</p>
                                    </div>
                                </div>
                            @else
                                {{-- JIKA STOK KOSONG --}}
                                @php
                                    $reservasiAktif = \App\Models\Reservation::where('user_id', Auth::id())
                                                        ->where('buku_id', $buku->id)
                                                        ->where('status', 'menunggu')
                                                        ->exists();
                                @endphp

                                @if($reservasiAktif)
                                    {{-- TAMPILAN JIKA SUDAH RESERVASI --}}
                                    <div class="p-6 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-4">
                                        <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-lg">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-rose-800 uppercase tracking-wider">Reservasi Aktif</p>
                                            <p class="text-[11px] text-rose-700 font-medium">Anda sudah masuk dalam daftar antrean menunggu buku ini.</p>
                                        </div>
                                    </div>
                                @else
                                    {{-- TAMPILAN TOMBOL UNTUK MENGAMBIL ANTREAN --}}
                                    <div class="p-6 bg-amber-50 border border-amber-100 rounded-2xl shadow-sm">
                                        <div class="flex items-center gap-4 mb-5">
                                            <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-lg">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-amber-800 uppercase tracking-wider">Antrean Reservasi</p>
                                                <p class="text-[11px] text-amber-700 font-medium">Dapatkan notifikasi WA otomatis saat buku ini tersedia.</p>
                                            </div>
                                        </div>

                                        <form action="{{ route('reservasi.store', $buku->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-amber-200 flex items-center justify-center gap-2">
                                                <i class="bi bi-calendar-plus-fill"></i> Ambil Antrean Reservasi
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endif

                        {{-- ADMIN / PUSTAKAWAN --}}
                        @elseif($userRole == 'admin' || $userRole == 'pustakawan')
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                                <p class="text-[10px] font-black text-slate-400 uppercase text-center mb-4 tracking-widest">Manajemen Data</p>
                                <a href="{{ route($userRole . '.buku.edit', $buku->id) }}" class="flex items-center justify-center gap-2 w-full py-4 bg-[#2D3E50] hover:bg-indigo-600 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest transition-all">
                                    <i class="bi bi-pencil-square"></i> Edit Informasi Buku
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <p class="mt-8 text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">
                SIPUSTAKA ITH &copy; 2026
            </p>
        </div>
    </main>

</body>
</html>
