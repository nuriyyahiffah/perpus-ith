<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Mahasiswa - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .hero-gradient { background: linear-gradient(180deg, #A7C5E0 0%, #F8FAFC 100%); }
        [x-cloak] { display: none !important; }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .floating-animation {
            animation: floating 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased" x-data="{ profileOpen: false }">

    {{-- NAVBAR --}}
    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-10">
                <span class="text-[10px] font-bold leading-tight uppercase tracking-wider hidden sm:block">
                    Digital<br><span class="text-yellow-400">Library ITH</span>
                </span>
            </div>

            <div class="hidden md:flex items-center space-x-8 text-[11px] font-bold uppercase tracking-widest">
                <a href="{{ route('mahasiswa.beranda') }}" class="text-yellow-400 border-b-2 border-yellow-400 pb-1">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-grid"></i> Katalog
                </a>
                <a href="{{ route('mahasiswa.usulan.create') }}" class="hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-journal-plus"></i> Usul Buku
                </a>
            </div>

            {{-- Profil --}}
            <div class="relative">
                <button @click="profileOpen = !profileOpen" class="flex items-center space-x-4 focus:outline-none group">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black uppercase text-emerald-400 group-hover:text-emerald-300 transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter">NIM: {{ Auth::user()->nomor_identitas }}</p>
                    </div>
                    <div class="relative">
                        <i class="bi bi-person-circle text-2xl text-emerald-400 group-hover:scale-110 transition-transform block"></i>
                        <span class="absolute -bottom-1 -right-1 block h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-[#2D3E50]"></span>
                    </div>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak 
                     class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-[60] text-slate-700">
                    <div class="px-5 py-3 border-b border-slate-50 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Status: Mahasiswa Aktif</p>
                    </div>
                    <a href="{{ route('notifikasi.index') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><i class="bi bi-bell"></i></div>
                        <span class="text-[11px] font-extrabold uppercase">Notifikasi</span>
                    </a>
        <a href="{{ route('profil.edit') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 hover:text-amber-600 transition-colors group">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all">
                <i class="bi bi-person-gear"></i>
            </div>
            <span class="text-[11px] font-extrabold uppercase">Edit Profil</span>
        </a>
                    </a>
                    <a href="{{ route('mahasiswa.riwayat') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 hover:text-emerald-600 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600"><i class="bi bi-clock-history"></i></div>
                        <span class="text-[11px] font-extrabold uppercase">Riwayat Pinjam</span>
                    </a>
                    <div class="border-t border-slate-100 mt-2 pt-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-4 px-5 py-3 text-rose-500 hover:bg-rose-50 font-bold text-left transition-all">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600"><i class="bi bi-box-arrow-right"></i></div>
                                <span class="text-[11px] font-extrabold uppercase">Keluar Sistem</span>
                            </button>
                        </form>
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
                    PERPUSTAKAAN <br> <span class="text-indigo-600 italic tracking-normal">ITH</span>
                </h1>
                <div class="h-1.5 w-20 bg-indigo-600 my-6 mx-auto md:mx-0"></div>
                <p class="text-slate-600 text-sm max-w-sm leading-relaxed italic mx-auto md:mx-0">
                    Temukan referensi terbaik yang telah dikurasi oleh dosen untuk mendukung perkuliahanmu di Prodi <strong>{{ Auth::user()->prodi }}</strong>.
                </p>

                <div class="mt-8 flex items-center justify-center md:justify-start space-x-3 bg-white/50 w-fit px-4 py-2 rounded-full border border-white/80 shadow-sm mx-auto md:mx-0">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <p class="text-[10px] font-bold text-slate-700 uppercase tracking-wide">
                        Selamat Datang, <span class="text-emerald-600">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                    </p>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-end relative mt-10 md:mt-0">
                <img src="{{ asset('images/books.png') }}" alt="Hero" class="w-full max-w-md drop-shadow-2xl z-10 floating-animation">
                <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-indigo-200/30 rounded-full blur-3xl"></div>
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="container mx-auto -mt-10 px-6 pb-20 relative z-20">
        
       <main class="container mx-auto -mt-10 px-6 pb-20 relative z-20">
    
    {{-- SECTION 1: REKOMENDASI PRODI (Style Dosen - MK di Bawah) --}}
    <section class="mb-16">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h2 class="text-xl font-black text-[#2D3E50] uppercase tracking-tighter leading-none">Rekomendasi Prodi {{ Auth::user()->prodi }}</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Buku pilihan dosen untuk menunjang mata kuliahmu</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($bukuProdi ?? [] as $item)
            <div class="group">
                <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-50 flex flex-col transition-all duration-300 hover:-translate-y-2 h-full">
                    {{-- Cover Buku --}}
                    <div class="aspect-[3/4] rounded-[2rem] bg-slate-100 mb-5 overflow-hidden relative shadow-inner">
                        <img src="{{ $item->buku->gambar_buku ? asset('images/' . $item->buku->gambar_buku) : asset('images/default-cover.jpg') }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    {{-- Judul & Penulis --}}
                    <div class="flex-grow">
                        <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-1 line-clamp-2">
                            {{ $item->buku->judul }}
                        </h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mb-4">
                            By: {{ $item->buku->penulis }}
                        </p>
                    </div>

                    {{-- POSISI MK: Di bawah (Sesuai Gambar Panel Dosen) --}}
                    <div class="mt-auto pt-4 border-t border-slate-50">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1">
                            <i class="bi bi-tag-fill text-indigo-500"></i> Referensi MK:
                        </p>
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @if($item->mata_kuliah)
                                @foreach(explode(',', $item->mata_kuliah) as $mk)
                                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[7px] font-black uppercase shadow-sm">
                                        {{ trim($mk) }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                        
                       {{-- TOMBOL: Diubah hanya untuk LIHAT DETAIL --}}
                <a href="{{ route('buku.detail', $item->buku->id) }}" class="w-full py-3 bg-[#2D3E50] hover:bg-indigo-600 text-white text-[10px] font-bold uppercase rounded-xl flex items-center justify-center transition-all">
                    <i class="bi bi-eye me-2"></i> Lihat Detail Buku
                </a>
            </div>
        </div>
    </div>
            @empty
            <div class="col-span-full py-10 text-center bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                <p class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Belum ada rekomendasi khusus prodi anda.</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- SECTION 2: BUKU POPULER --}}
    <section class="mb-16">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h2 class="text-xl font-black text-[#2D3E50] uppercase tracking-tighter leading-none">Buku Populer</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Buku yang paling sering dipinjam mahasiswa</p>
            </div>
            <a href="{{ route('katalog.index') }}" class="text-[10px] font-black text-indigo-600 uppercase border-b-2 border-indigo-600 pb-1 hover:text-indigo-800 transition">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($bukuPopuler ?? [] as $buku)
            <div class="group">
                <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-50 flex flex-col transition-all duration-300 hover:-translate-y-2 h-full">
                    {{-- Cover Buku --}}
                    <div class="aspect-[3/4] rounded-[2rem] bg-slate-100 mb-5 overflow-hidden relative shadow-inner">
                        <img src="{{ $buku->gambar_buku ? asset('images/' . $buku->gambar_buku) : asset('images/default-cover.jpg') }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        {{-- Badge Populer --}}
                        <div class="absolute top-3 right-3 bg-rose-500 text-white text-[8px] font-black uppercase px-2 py-1 rounded-full shadow-lg">
                            <i class="bi bi-fire"></i> Populer
                        </div>
                    </div>

                    {{-- Judul & Penulis --}}
                    <div class="flex-grow">
                        <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-1 line-clamp-2">
                            {{ $buku->judul }}
                        </h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mb-4">
                            By: {{ $buku->penulis }}
                        </p>
                    </div>

                    {{-- TOMBOL: Lihat Detail --}}
                    <a href="{{ route('buku.detail', $buku->id) }}" class="w-full py-3 bg-rose-500 hover:bg-rose-600 text-white text-[10px] font-bold uppercase rounded-xl flex items-center justify-center transition-all">
                        <i class="bi bi-eye me-2"></i> Lihat Detail Buku
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-10 text-center bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                <p class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Belum ada data peminjaman untuk menentukan buku populer.</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- SECTION 3: KOLEKSI UMUM --}}
    <section>
        <div class="mb-8">
            <h2 class="text-xl font-black text-[#2D3E50] uppercase tracking-tighter">Koleksi Terbaru</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($semuaBuku ?? [] as $buku)
            <div class="group">
                <div class="bg-white rounded-[2rem] p-5 shadow-lg shadow-slate-100 border border-white flex flex-col transition-all duration-300 hover:shadow-xl h-full">
                    <div class="aspect-[3/4] rounded-2xl bg-slate-100 mb-4 overflow-hidden">
                        <img src="{{ $buku->gambar_buku ? asset('images/' . $buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-[#2D3E50] text-[12px] uppercase leading-tight line-clamp-2">{{ $buku->judul }}</h3>
                    <div class="mt-auto pt-4 flex justify-between items-center">
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">{{ $buku->penulis }}</span>
                        <a href="{{ route('buku.detail', $buku->id) }}" class="text-indigo-600 hover:translate-x-1 transition-transform">
                            <i class="bi bi-arrow-right-circle-fill text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-bookmark-plus text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Belum ada rekomendasi prodi</p>
                <a href="{{ route('katalog.index') }}" class="inline-block text-[10px] font-black text-blue-600 uppercase border-b-2 border-blue-600 pb-1 hover:text-blue-800 transition">Jelajahi Katalog Lengkap</a>
            </div>
            @endforelse
        </div>
    </section>
</main>

    <footer class="py-12 bg-white border-t border-slate-100">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.3em] mb-2">SIPUSTAKA DIGITAL LIBRARY</p>
            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Institut Teknologi Bacharuddin Jusuf Habibie (ITH)</p>
        </div>
    </footer>

</body>
</html>