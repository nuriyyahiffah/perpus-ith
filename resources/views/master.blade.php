<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library | Institut Teknologi Bacharuddin Jusuf Habibie</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC; /* Mengganti ke slate muda agar memberi efek kontras kedalaman */
        }
        [x-cloak] { display: none !important; }
        .nav-link-active {
            color: #fbbf24; /* Yellow ITH */
            border-bottom: 3px solid #fbbf24;
        }
    </style>
</head>
<body class="antialiased text-slate-800">

    <nav class="flex items-center justify-between px-8 md:px-16 py-5 bg-[#2D3E50] text-white sticky top-0 z-50 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-400/20">
                <i class="bi bi-book-half text-[#2D3E50] text-xl"></i>
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-[11px] font-black text-yellow-400 tracking-wider uppercase">Perpustakaan</span>
                <span class="text-[10px] font-medium text-slate-300 tracking-tight uppercase">Institut Teknologi BJ Habibie</span>
            </div>
        </div>

        <div class="flex items-center gap-8 md:gap-12">
            <a href="/" class="text-[11px] font-extrabold uppercase tracking-[0.2em] nav-link-active pb-1">
                Beranda
            </a>

            <a href="{{ route('login') }}" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 rounded-full text-[11px] font-black uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-emerald-500/20">
                <i class="bi bi-person-fill text-sm"></i> Masuk
            </a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="py-12 bg-[#2D3E50] border-t border-white/5 mt-20">
        <div class="text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.4em]">
                &copy; {{ date('Y') }} ITH Digital Library • Parepare
            </p>
        </div>
    </footer>

</body>
</html>
