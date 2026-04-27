<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #1E293B; scroll-behavior: smooth; }
        /* Gradient lebih halus */
        .hero-gradient { background: linear-gradient(180deg, #E2E8F0 0%, #F8FAFC 100%); }
        /* Animasi melayang untuk gambar */
        .float { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>

<body class="antialiased">

    <nav class="bg-[#1E293B]/90 backdrop-blur-md text-white py-4 sticky top-0 z-50 border-b border-white/10">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-10 brightness-110">
                <div class="hidden sm:block border-l border-white/20 pl-4">
                    <span class="text-[11px] font-black leading-none uppercase tracking-tighter block">
                        Digital<br><span class="text-yellow-400">Library ITH</span>
                    </span>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-8 text-[10px] font-black uppercase tracking-[0.15em]">
                <a href="{{ route('beranda') }}" class="{{ Route::is('beranda') ? 'text-yellow-400' : 'text-slate-300 hover:text-white' }} transition">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="text-slate-300 hover:text-white transition">Katalog</a>
                
                @guest
                    <a href="#tentang" class="text-slate-300 hover:text-white transition">Tentang</a>
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
                <h1 class="text-7xl font-[900] text-[#1E293B] leading-[0.85] uppercase tracking-tighter mb-8">
                    Perpustakaan <br> <span class="text-blue-600 italic">Digital ITH</span>
                </h1>
                <p class="text-slate-500 text-sm font-medium max-w-sm leading-relaxed mb-10">
                    Sistem informasi perpustakaan berbasis web untuk mendukung riset dan teknologi di lingkungan <span class="text-slate-800 font-bold italic text-xs">Institut Teknologi BJ Habibie.</span>
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('katalog.index') }}" class="bg-[#1E293B] text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:shadow-2xl transition-all active:scale-95">Jelajahi Koleksi</a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center md:justify-end relative">
                <div class="absolute w-72 h-72 bg-blue-400/20 rounded-full blur-3xl -z-10 animate-pulse"></div>
                <img src="{{ asset('images/books.png') }}" alt="Hero" class="w-full max-w-md drop-shadow-[0_35px_35px_rgba(0,0,0,0.25)] float">
            </div>
        </div>
    </header>

    <main class="container mx-auto px-8 -mt-16 relative z-10 pb-20">
        @guest
            <section class="bg-white/80 backdrop-blur-xl p-16 rounded-[4rem] shadow-2xl shadow-slate-200/50 border border-white text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <i class="bi bi-shield-lock-fill text-4xl text-slate-300"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter mb-4">Akses Terbatas</h3>
                <p class="text-slate-400 text-sm font-medium mb-10 max-w-md mx-auto">Untuk menjaga kualitas layanan dan hak cipta, akses katalog lengkap hanya tersedia bagi Civitas Akademika ITH.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-12 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-200">Masuk Akun</a>
                    <a href="{{ route('register') }}" class="bg-white text-slate-600 border border-slate-200 px-12 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition">Registrasi</a>
                </div>
            </section>
        @else
            <section id="katalog">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-[900] uppercase italic tracking-tighter text-slate-800">Koleksi <span class="text-blue-600">Terbaru</span></h2>
                        <div class="h-1 w-12 bg-yellow-400 mt-2"></div>
                    </div>
                    <a href="{{ route('katalog.index') }}" class="text-[10px] font-black uppercase text-blue-600 border-b-2 border-blue-100 hover:border-blue-600 transition pb-1">Lihat Semua</a>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                    @forelse($bukuTerbaru as $buku)
                        <x-card-buku :buku="$buku" />
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
                             <p class="text-slate-400 italic font-bold uppercase tracking-widest text-xs">Belum ada koleksi buku baru.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        @endguest
    </main>

    <footer class="bg-[#1E293B] text-white py-16">
        <div class="container mx-auto px-8 text-center">
            <img src="{{ asset('images/logo_ith.png') }}" class="h-12 mx-auto mb-8 opacity-50 grayscale hover:grayscale-0 transition duration-500">
            <div class="h-px w-20 bg-white/10 mx-auto mb-8"></div>
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.6em]">&copy; 2026 SIPUSTAKA ITH • Kampus Parepare</p>
        </div>
    </footer>

</body>
</html>