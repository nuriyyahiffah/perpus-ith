<header class="bg-[#2D3E50] text-white shadow-md w-full py-4 px-6 sticky top-0 z-50 border-b border-white/10">
    <div class="max-w-7xl mx-auto flex items-center justify-between relative">

        {{-- 1. LOGIKA DETEKSI ROUTE DASHBOARD BERDASARKAN ROLE (UNTUK TOMBOL KEMBALI) --}}
        @php
            $dashRoute = 'beranda'; // Route default jika tidak ada role yang cocok atau guest

            if(Auth::check()) {
                $role = Auth::user()->role;

                if($role === 'admin') {
                    $dashRoute = 'admin.dashboard';
                } elseif($role === 'pustakawan') {
                    $dashRoute = 'pustakawan.dashboard';
                } elseif($role === 'dosen') {
                    $dashRoute = 'dosen.beranda';
                } elseif($role === 'kaprodi') {
                    $dashRoute = 'dosen.beranda';
                } elseif($role === 'mahasiswa') {
                    $dashRoute = 'mahasiswa.beranda';
                }
            }
        @endphp

        <div class="flex items-center space-x-4">

            <a href="{{ Auth::check() ? route($dashRoute) : url('/') }}" class="p-2 rounded-full hover:bg-white/10 transition duration-200 group focus:outline-none" title="Kembali ke Dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white group-hover:text-yellow-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div class="h-8 w-[1px] bg-white/20"></div>

            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-12 w-auto object-contain drop-shadow-md">

                <div class="flex flex-col justify-center leading-none">
                    <h1 class="text-base font-extrabold tracking-wider uppercase">
                        PERPUSTAKAAN
                    </h1>
                    <p class="text-[10px] font-bold text-yellow-400 tracking-wide mt-1 uppercase">
                        INSTITUT TEKNOLOGI BACHARUDDIN JUSUF HABIBIE
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-6">
            @auth
                <div class="text-right hidden sm:flex flex-col leading-none">
                    <span class="block text-[9px] text-slate-400 uppercase font-black tracking-widest mb-1">PENGGUNA AKTIF</span>
                    <span class="text-xs font-bold text-slate-200">{{ Auth::user()->name }}</span>
                </div>
            @else
                <a href="{{ route('login') }}" class="bg-[#10B981] hover:bg-[#059669] text-white text-[10px] font-black px-8 py-3 rounded-xl transition shadow-lg shadow-emerald-500/20 active:scale-95 uppercase tracking-widest">
                    Masuk Akun
                </a>
            @endauth
        </div>

    </div>
</header>
