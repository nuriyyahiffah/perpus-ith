<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Dosen - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js untuk fitur buka-tutup menu profil --}}
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

            {{-- Menu Tengah --}}
            <div class="hidden md:flex items-center space-x-8 text-[11px] font-bold uppercase tracking-widest">
                <a href="{{ route('dosen.beranda') }}" class="text-yellow-400 border-b-2 border-yellow-400 pb-1">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-journal-bookmark"></i> Katalog
                </a>
                <a href="{{ route('dosen.usulan.create') }}" class="hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-journal-plus"></i> Usul Buku
                </a>
                <a href="{{ route('dosen.claim.index') }}" class="hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-qr-code-scan"></i> Klaim Buku
                </a>
            </div>

            {{-- Profil & Dropdown --}}
            <div class="relative">
                <button @click="profileOpen = !profileOpen" class="flex items-center space-x-4 focus:outline-none group">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black uppercase text-emerald-400 group-hover:text-emerald-300 transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter">NIP: {{ Auth::user()->nomor_identitas }}</p>
                    </div>
                    <div class="relative">
                        <i class="bi bi-person-circle text-2xl text-emerald-400 group-hover:scale-110 transition-transform"></i>
                        <span class="absolute -bottom-1 -right-1 block h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-[#2D3E50]"></span>
                    </div>
                </button>

                {{-- Dropdown Menu (Desain yang Dikembalikan) --}}
                <div x-show="profileOpen" 
                     @click.away="profileOpen = false" 
                     x-cloak 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-[60] text-slate-700">
                    
                    <div class="px-5 py-3 border-b border-slate-50 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Status: Dosen Pengajar</p>
                    </div>

                    {{-- Menu: Edit Profil --}}
                    <a href="{{ route('profil.edit') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 hover:text-amber-600 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-sm">
                            <i class="bi bi-person-gear"></i>
                        </div>
                        <span class="text-[11px] font-extrabold uppercase">Edit Profil</span>
                    </a>

                    {{-- Menu: Riwayat Usulan --}}
                    <a href="{{ route('dosen.usulan.riwayat') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 hover:text-blue-600 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-all shadow-sm">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="text-[11px] font-extrabold uppercase">Riwayat Usulan</span>
                    </a>

                    {{-- Menu: Riwayat Klaim --}}
                    <a href="{{ route('dosen.klaim.riwayat') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 hover:text-purple-600 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-500 group-hover:text-white transition-all shadow-sm">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <span class="text-[11px] font-extrabold uppercase">Riwayat Klaim Buku</span>
                    </a>

                    {{-- Tombol Keluar --}}
                    <div class="border-t border-slate-100 mt-2 pt-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 text-rose-500 hover:bg-rose-50 font-bold text-left transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-500 group-hover:text-white transition-all shadow-sm">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>
                                <span class="text-[11px] font-extrabold uppercase tracking-widest">Keluar Sistem</span>
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
                    PANEL <br> <span class="text-indigo-600 italic tracking-normal">DOSEN</span>
                </h1>
                <div class="h-1.5 w-20 bg-indigo-600 my-6 mx-auto md:mx-0"></div>
                <p class="text-slate-600 text-sm max-w-sm leading-relaxed italic mx-auto md:mx-0">
                    Kelola usulan referensi dan buku hibah untuk mahasiswa Prodi <strong>{{ Auth::user()->prodi }}</strong>.
                </p>
            </div>
            <div class="md:w-1/2 flex justify-end mt-10 md:mt-0">
                <img src="{{ asset('images/books.png') }}" alt="Hero" class="w-full max-w-md drop-shadow-2xl">
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="container mx-auto -mt-10 px-6 pb-20 relative z-20 space-y-20">
        
        {{-- SECTION 1: KOLEKSI KLAIM PRODI --}}
        <section>
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-black text-[#2D3E50] uppercase tracking-tighter text-2xl">Koleksi Klaim Prodi</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Buku yang telah disetujui untuk {{ Auth::user()->prodi }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($bukuSaya as $item)
                <div class="bg-white rounded-[2.5rem] p-5 shadow-xl shadow-slate-200/50 border border-white flex flex-col h-full transition-all duration-300 hover:shadow-2xl">
                    <div class="aspect-[3/4] rounded-3xl bg-slate-100 mb-5 overflow-hidden shadow-inner relative group">
                        <img src="{{ $item->buku && $item->buku->gambar_buku ? asset('images/' . $item->buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <div class="px-2 flex-grow">
                        <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-1 line-clamp-2 italic">{{ $item->buku?->judul ?? 'Judul Tidak Tersedia' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-4">BY: {{ $item->buku?->penulis ?? 'ANONIM' }}</p>
                        <hr class="border-slate-50 mb-4">
                    </div>
                    <div class="px-2 pb-2 mt-auto">
                        <a href="{{ route('buku.detail', $item->buku->id) }}" class="w-full bg-[#2D3E50] hover:bg-indigo-600 text-white py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all duration-300 shadow-lg shadow-slate-200">
                            <i class="bi bi-eye text-sm"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Lihat Detail Buku</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest italic">Belum ada koleksi klaim.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- SECTION 2: BUKU POPULER --}}
        <section>
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-black text-[#2D3E50] uppercase tracking-tighter text-2xl">Buku Populer</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Buku yang paling sering dipinjam mahasiswa</p>
                </div>
                <a href="{{ route('katalog.index') }}" class="text-[10px] font-black text-indigo-600 uppercase border-b-2 border-indigo-600 pb-1 hover:text-indigo-800 transition">
                    Lihat Semua
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($bukuPopuler ?? [] as $buku)
                <div class="bg-white rounded-[2.5rem] p-5 shadow-xl shadow-slate-200/50 border border-white flex flex-col h-full transition-all duration-300 hover:shadow-2xl">
                    <div class="aspect-[3/4] rounded-3xl bg-slate-100 mb-5 overflow-hidden shadow-inner relative group">
                        <img src="{{ $buku->gambar_buku ? asset('images/' . $buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        {{-- Badge Populer --}}
                        <div class="absolute top-3 right-3 bg-rose-500 text-white text-[8px] font-black uppercase px-2 py-1 rounded-full shadow-lg">
                            <i class="bi bi-fire"></i> Populer
                        </div>
                    </div>
                    <div class="px-2 flex-grow">
                        <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-1 line-clamp-2 italic">{{ $buku->judul }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-4">BY: {{ $buku->penulis ?? 'ANONIM' }}</p>
                        <hr class="border-slate-50 mb-4">
                    </div>
                    <div class="px-2 pb-2 mt-auto">
                        <a href="{{ route('buku.detail', $buku->id) }}" class="w-full bg-rose-500 hover:bg-rose-600 text-white py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all duration-300 shadow-lg shadow-slate-200">
                            <i class="bi bi-eye text-sm"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Lihat Detail Buku</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest italic">Belum ada data peminjaman untuk menentukan buku populer.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- SECTION 3: KOLEKSI TERBARU --}}
        <section>
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-black text-[#2D3E50] uppercase tracking-tighter text-2xl">Koleksi Terbaru</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Daftar buku yang baru saja ditambahkan ke sistem</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($bukuTerbaru as $buku)
                <div class="bg-white rounded-[2.5rem] p-5 shadow-xl shadow-slate-200/50 border border-white flex flex-col h-full transition-all duration-300 hover:shadow-2xl">
                    <div class="aspect-[3/4] rounded-3xl bg-slate-100 mb-5 overflow-hidden shadow-inner relative group">
                        <img src="{{ $buku->gambar_buku ? asset('images/' . $buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <div class="px-2 flex-grow">
                        <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-1 line-clamp-2 italic">{{ $buku->judul }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-4">BY: {{ $buku->penulis ?? 'ANONIM' }}</p>
                        <hr class="border-slate-50 mb-4">
                    </div>
                    <div class="px-2 pb-2 mt-auto">
                        <a href="{{ route('buku.detail', $buku->id) }}" class="w-full bg-[#2D3E50] hover:bg-indigo-600 text-white py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all duration-300 shadow-lg shadow-slate-200">
                            <i class="bi bi-eye text-sm"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Lihat Detail Buku</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest italic">Tidak ada buku terbaru.</p>
                </div>
                @endforelse
            </div>
        </section>

    </main>

    <footer class="py-12 bg-white border-t border-slate-100">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.3em] mb-2">SIPUSTAKA DIGITAL LIBRARY</p>
            <p class="text-[8px] text-slate-400 font-bold uppercase italic text-2xl">Institut Teknologi Bacharuddin Jusuf Habibie (ITH)</p>
        </div>
    </footer>

</body>
</html>