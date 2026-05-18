<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library ITH</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #1E293B; scroll-behavior: smooth; }
        /* Gradasi warna latar belakang disamakan persis dengan beranda mahasiswa */
        .hero-gradient { background: linear-gradient(180deg, #E2E8F0 0%, #F8FAFC 100%); }
        /* Animasi melayang gambar buku */
        .float { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased" x-data="{ search: '', selectedBook: null }">

    <div class="bg-[#1E293B] text-slate-300 text-[10px] font-bold uppercase tracking-wider py-2.5 border-b border-white/10">
    <div class="container mx-auto flex justify-between items-center px-6">
        <span class="flex items-center gap-2">
            <i class="bi bi-clock text-yellow-400"></i> SENIN - JUMAT: 07:30 - 17:00 WITA
        </span>
        <span class="flex items-center gap-2">
            <i class="bi bi-geo-alt text-yellow-400"></i> KAMPUS 2 ITH | CAPPA GALUNG, KEC. BACUKIKI, KOTA PAREPARE, SULAWESI SELATAN 91125
        </span>
    </div>
</div>

    <nav class="bg-[#1E293B]/90 backdrop-blur-md text-white py-4 sticky top-0 z-50 border-b border-white/5">
        <div class="container mx-auto flex justify-between items-center px-6">
            
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-10">
                <div class="leading-tight border-l border-white/20 pl-3">
                    <span class="text-xs font-[800] tracking-wider block uppercase">PERPUSTAKAAN</span>
                    <span class="text-[9px] font-bold text-yellow-400 tracking-tight block uppercase leading-none mt-0.5">
                        INSTITUT TEKNOLOGI BACHARUDDIN JUSUF HABIBIE
                    </span>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-8 text-[10px] font-black uppercase tracking-[0.15em]">
                <a href="{{ route('beranda') }}" class="{{ Route::is('beranda') ? 'text-yellow-400' : 'text-slate-300 hover:text-white' }} transition">Beranda</a>
                <a href="{{ route('katalog.publik') }}" class="text-slate-300 hover:text-white transition">Katalog</a>
                @guest
                    <a href="#katalog" class="text-slate-300 hover:text-white transition">Koleksi</a>
                @else
                    @if(Auth::user()->role == 'dosen')
                        <a href="{{ route('dosen.usulan.buku') }}" class="flex items-center gap-2 text-slate-300 hover:text-white transition">
                            <i class="bi bi-plus-circle"></i> Usul Buku
                        </a>
                    @endif
                @endguest
            </div>

            <div class="flex items-center space-x-5">
                @guest
                    <a href="{{ route('login') }}" class="bg-blue-600 px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-500 transition shadow-lg shadow-blue-500/20">Masuk</a>
                @else
                    <div class="relative group">
                        <button class="flex items-center space-x-3 bg-white/5 p-1 pr-4 rounded-full hover:bg-white/10 transition">
                            <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-slate-900">
                                <i class="bi bi-person-fill text-lg"></i>
                            </div>
                            <div class="text-left hidden sm:block">
                                <p class="text-[10px] font-black uppercase tracking-wider leading-none">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                <p class="text-[8px] text-slate-400 font-bold uppercase mt-1">{{ Auth::user()->role }}</p>
                            </div>
                        </button>

                        <div class="absolute right-0 w-56 mt-3 bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 translate-y-2 group-hover:translate-y-0">
                            <div class="p-6 text-slate-800">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Akses Cepat</p>
                                <div class="space-y-1">
                                    <a href="#" class="flex items-center gap-3 p-3 text-[11px] font-bold hover:bg-slate-50 rounded-2xl transition">
                                        <i class="bi bi-grid-1x2 text-blue-600"></i> Dashboard
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="w-full flex items-center gap-3 p-3 text-[11px] font-bold text-red-500 hover:bg-red-50 rounded-2xl transition">
                                            <i class="bi bi-box-arrow-right"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <header class="hero-gradient pt-24 pb-32 overflow-hidden">
        <div class="container mx-auto px-8 flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 mb-16 md:mb-0">
                <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-6 italic">Parepare, Indonesia</span>

                <h1 class="text-5xl md:text-6xl font-[800] text-slate-800 leading-[1.1] uppercase tracking-tighter mb-6">
                    PERPUSTAKAAN <br>
                    <span class="text-blue-600 font-[900] italic">DIGITAL ITH</span>
                </h1>

                <p class="text-slate-500 text-sm font-medium max-w-sm leading-relaxed mb-10">
                    Sistem informasi perpustakaan berbasis web untuk mendukung riset dan teknologi di lingkungan <span class="text-slate-800 font-bold italic text-xs">Institut Teknologi BJ Habibie.</span>
                </p>

                <div class="max-w-md bg-white p-2 rounded-2xl shadow-xl flex items-center gap-2 border border-slate-200/60">
                    <div class="flex items-center pl-3 text-slate-400">
                        <i class="bi bi-search text-sm"></i>
                    </div>
                    <input type="text" x-model="search"
                        class="w-full bg-transparent border-none py-2 px-1 text-slate-700 text-xs font-bold placeholder:text-slate-400 focus:outline-none"
                        placeholder="Cari judul buku, penulis, atau kategori...">
                </div>
            </div>

            <div class="md:w-1/2 flex justify-center md:justify-end relative">
                <div class="absolute w-72 h-72 bg-blue-400/20 rounded-full blur-3xl -z-10 animate-pulse"></div>
                <img src="{{ asset('images/books.png') }}" alt="Koleksi Buku" class="w-full max-w-md drop-shadow-[0_35px_35px_rgba(0,0,0,0.25)] float">
            </div>
        </div>
    </header>

    <main class="container mx-auto px-8 -mt-16 relative z-10 pb-20" id="katalog">
        <section class="bg-white p-8 md:p-12 rounded-[3.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">

            <div class="mb-12">
                <h2 class="text-2xl md:text-3xl font-[900] uppercase italic tracking-tight text-slate-800 flex items-center gap-2">
                    KOLEKSI <span class="text-blue-600">TERBARU</span>
                </h2>
                <div class="h-1 w-16 bg-yellow-400 mt-2 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse($bukuTerbaru as $buku)
                    @php
                        $bookDetail = json_encode([
                            'id' => $buku->id,
                            'judul' => $buku->judul,
                            'penulis' => $buku->penulis,
                            'penerbit' => $buku->penerbit ?? '-',
                            'tahun' => $buku->tahun_terbit ?? '-',
                            'kategori' => $buku->kategori ?? 'Umum',
                            'isbn' => $buku->isbn ?? '-',
                            'stok' => $buku->stok_tersedia ?? 0,
                            'deskripsi' => $buku->deskripsi ?? 'Tidak ada ringkasan sinopsis untuk buku ini.',
                            'cover' => $buku->cover ? asset('storage/' . $buku->cover) : 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=400'
                        ]);
                    @endphp

                    <div @click='selectedBook = {!! $bookDetail !!}'
                         x-show="search === '' || '{{ strtolower($buku->judul) }}'.includes(search.toLowerCase()) || '{{ strtolower($buku->penulis) }}'.includes(search.toLowerCase())"
                         class="cursor-pointer transition transform hover:scale-105 duration-300">
                        <x-card-buku :buku="$buku" />
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border border-dashed border-slate-200">
                        <i class="bi bi-journal-x text-5xl text-slate-300 block mb-3"></i>
                        <p class="text-slate-400 italic font-bold uppercase tracking-widest text-xs">Belum ada koleksi data buku di database.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
         x-show="selectedBook !== null" x-cloak x-transition>

        <div class="bg-white rounded-[2.5rem] w-full max-w-2xl p-6 md:p-8 shadow-2xl border border-slate-100 overflow-y-auto max-h-[90vh]"
             @click.away="selectedBook = null">

            <div class="flex justify-between items-start mb-6 gap-4">
                <div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-wider" x-text="selectedBook?.kategori"></span>
                    <h3 class="text-xl md:text-2xl font-black text-slate-800 mt-2 leading-tight" x-text="selectedBook?.judul"></h3>
                </div>
                <button @click="selectedBook = null" class="text-slate-400 hover:text-slate-600 text-xl transition transform hover:rotate-90">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col items-center md:items-start">
                    <div class="w-40 md:w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-lg border border-slate-200">
                        <img :src="selectedBook?.cover" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="md:col-span-2 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Sinopsis</h4>
                            <p class="text-slate-600 text-xs font-medium leading-relaxed mt-1" x-text="selectedBook?.deskripsi"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase">Penulis</p>
                                <p class="font-bold text-slate-700" x-text="selectedBook?.penulis"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase">Penerbit</p>
                                <p class="font-bold text-slate-700" x-text="selectedBook?.penerbit"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase">Tahun</p>
                                <p class="font-bold text-slate-700" x-text="selectedBook?.tahun"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase">ISBN</p>
                                <p class="font-bold text-slate-700" x-text="selectedBook?.isbn"></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded-2xl flex items-center justify-between text-xs font-bold"
                         :class="selectedBook?.stok > 0 ? 'bg-emerald-50 text-emerald-800' : 'bg-red-50 text-red-800'">
                        <span x-text="selectedBook?.stok > 0 ? 'Stok Tersedia ('+selectedBook?.stok+')' : 'Stok Kosong'"></span>

                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition">
                                Login untuk Pinjam
                            </a>
                        @else
                            <button class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition">
                                Ajukan Peminjaman
                            </button>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-[#1E293B] text-white py-16">
        <div class="container mx-auto px-8 text-center">
            <img src="{{ asset('images/logo_ith.png') }}" class="h-12 mx-auto mb-8 opacity-50 grayscale hover:grayscale-0 transition duration-500">
            <div class="h-px w-20 bg-white/10 mx-auto mb-8"></div>
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.6em]">&copy; 2026 SIPUSTAKA ITH • Kampus Parepare</p>
        </div>
    </footer>

</body>
</html>