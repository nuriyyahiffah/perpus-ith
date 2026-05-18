<aside class="w-72 bg-[#10192A] text-white hidden lg:flex flex-col shrink-0 sticky top-0 h-screen sidebar-scroll font-medium transition-none">

    {{-- 0. BRANDING --}}
    <div class="p-8 pb-10 flex items-center gap-4 bg-[#10192A] sticky top-0 z-10">
        <div class="w-20 h-20 bg-yellow-400 rounded-3xl flex items-center justify-center shadow-xl shadow-yellow-400/20 shrink-0">
            <i class="bi bi-book-half text-[#10192A] text-4xl"></i>
        </div>
        <div class="flex flex-col">
            <span class="font-black text-2xl tracking-tighter text-yellow-400 leading-none">ADMIN PANEL</span>
            <span class="text-xs text-slate-500 font-bold tracking-widest mt-2">ITH LIBRARY</span>
        </div>
    </div>



    {{-- Navigasi Utama --}}
    <nav class="flex-1 px-6 space-y-2 pb-10 overflow-y-auto custom-scrollbar">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-5 px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-400 text-[#10192A] shadow-lg shadow-yellow-400/10' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
            <i class="bi bi-microsoft text-xl"></i>
            <span class="text-[13px] font-black uppercase tracking-wider">Dashboard</span>
        </a>

     {{-- MANAJEMEN PENGGUNA --}}
<div x-data="{ open: {{ request()->is('kelola/mahasiswa*') || request()->is('kelola/dosen*') || request()->is('admin/pustakawan*') || request()->is('kelola/pegawai*') || request()->is('kelola/kategori-anggota*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
        class="w-full flex items-center justify-between px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->is('kelola/mahasiswa*') || request()->is('kelola/dosen*') || request()->is('admin/pustakawan*') || request()->is('kelola/pegawai*') || request()->is('kelola/kategori-anggota*') ? 'bg-yellow-400 text-[#10192A]' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
        <div class="flex items-center gap-5">
            <i class="bi bi-people text-xl"></i>
            <span class="text-[13px] font-black uppercase tracking-wider">Manajemen Pengguna</span>
        </div>
        <i class="bi bi-chevron-down text-[10px] transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" x-cloak x-transition class="mt-1 ml-9 space-y-1 border-l-2 border-slate-800/50 pl-6">
        <a href="{{ route('shared.mahasiswa.index') }}" class="block py-2 text-xs {{ request()->routeIs('shared.mahasiswa.*') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Data Mahasiswa</a>
        <a href="{{ route('shared.dosen.index') }}" class="block py-2 text-xs {{ request()->routeIs('shared.dosen.*') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Data Dosen</a>
        <a href="{{ route('shared.pegawai.index') }}" class="block py-2 text-xs {{ request()->routeIs('shared.pegawai.*') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Data Pegawai</a>
        <a href="{{ route('admin.pustakawan.index') }}" class="block py-2 text-xs {{ request()->routeIs('admin.pustakawan.*') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Data Pustakawan</a>
        <a href="{{ route('shared.kategori-anggota.index') }}" class="block py-2 text-xs {{ request()->routeIs('shared.kategori-anggota.*') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Kategori Pengguna</a>
    </div>
</div>

{{--  MANAJEMEN BUKU --}}
<div x-data="{ open: {{ request()->is('kelola/buku*') || request()->is('kelola/kategori-buku*') || request()->is('shared/klasifikasi*') || request()->routeIs('klasifikasi.*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
        class="w-full flex items-center justify-between px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->is('kelola/buku*') || request()->is('kelola/kategori-buku*') || request()->is('shared/klasifikasi*') || request()->routeIs('klasifikasi.*') ? 'bg-yellow-400 text-[#10192A]' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
        <div class="flex items-center gap-5">
            <i class="bi bi-book-half text-xl"></i>
            <span class="text-[13px] font-black uppercase tracking-wider">Manajemen Buku</span>
        </div>
        <i class="bi bi-chevron-down text-[10px] transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak x-transition class="mt-1 ml-9 space-y-1 border-l-2 border-slate-800/50 pl-6">
        <a href="{{ route('shared.buku.index') }}" class="block py-2 text-xs {{ request()->is('kelola/buku') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Katalog Buku</a>
        <a href="{{ route('shared.kategori-buku.index') }}" class="block py-2 text-xs {{ request()->routeIs('shared.kategori-buku.*') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Kategori Prodi</a>
        
        {{-- Sub Menu Baru: Klasifikasi DDC --}}
        <a href="{{ route('shared.klasifikasi.index') }}" class="block py-2 text-xs {{ request()->routeIs('klasifikasi.*') || request()->is('shared/klasifikasi*') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Klasifikasi DDC</a>
    </div>
</div>

        {{-- SIRKULASI --}}
        <div x-data="{ open: {{ request()->is('kelola/transaksi*') || request()->is('kelola/peminjaman*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->is('kelola/transaksi*') || request()->is('kelola/peminjaman*') ? 'bg-yellow-400 text-[#10192A]' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-5">
                    <i class="bi bi-arrow-down-up text-xl"></i>
                    <span class="text-[13px] font-black uppercase tracking-wider">Sirkulasi</span>
                </div>
                <i class="bi bi-chevron-down text-[10px] transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-cloak x-transition class="mt-1 ml-9 space-y-1 border-l-2 border-slate-800/50 pl-6">
                <a href="{{ route('shared.transaksi.index') }}" class="block py-2 text-xs {{ request()->routeIs('shared.transaksi.index') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Daftar Transaksi</a>
                <a href="{{ route('shared.usulan.index') }}" class="block py-2 text-xs {{ request()->routeIs('shared.usulan.index') ? 'text-yellow-400 font-bold' : 'text-slate-500 hover:text-white' }}">Konfirmasi Usulan Buku</a>
            </div>
        </div>

            {{-- Menu Kelola Reservasi (Shared) --}}
<a href="{{ route('reservasi.index') }}"
   class="flex items-center gap-3 px-6 py-3 transition-all duration-300 group
   {{ request()->routeIs('reservasi.index') ? 'bg-[#FFD700] rounded-r-full text-[#1E293B]' : 'hover:bg-white/5 text-slate-400 hover:text-white' }}">

    <div class="p-2 rounded-lg transition-all
        {{ request()->routeIs('reservasi.index') ? 'bg-[#1E293B] text-[#FFD700]' : 'bg-transparent text-slate-400 group-hover:text-white' }}">
        <i class="bi bi-calendar-check"></i>
    </div>

    <span class="text-[11px] font-black uppercase tracking-wider">
        Kelola Reservasi
    </span>
</a>

        {{--  LAPORAN BULANAN --}}
        <a href="{{ route('shared.laporan.bulanan') }}"
            class="flex items-center gap-5 px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->routeIs('shared.laporan.bulanan') ? 'bg-yellow-400 text-[#10192A]' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
            <i class="bi bi-calendar-range text-xl"></i>
            <span class="text-[13px] font-black uppercase tracking-wider">Laporan Bulanan</span>
        </a>

        {{--  PENGATURAN (BARU) --}}
        <a href="{{ route('shared.setting.index') }}"
            class="flex items-center gap-5 px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->is('admin/pengaturan*') ? 'bg-yellow-400 text-[#10192A]' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
            <i class="bi bi-gear text-xl"></i>
            <span class="text-[13px] font-black uppercase tracking-wider">Pengaturan</span>
            @if(request()->routeIs('shared.setting.index'))
            <div class="ms-auto w-1.5 h-1.5 rounded-full bg-blue-400"></div>
        @endif
        </a>

        {{--  BUKU TAMU --}}
        <div class="menu-item space-y-1">
            <a href="{{ route('shared.buku-tamu.index') }}"
                class="flex items-center gap-5 px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->routeIs('shared.buku-tamu.index') ? 'bg-yellow-400 text-[#10192A]' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
                <i class="bi bi-person-lines-fill text-xl"></i>
                <span class="text-[13px] font-black uppercase tracking-wider">Buku Tamu</span>
            </a>
            <a href="{{ route('buku-tamu.create') }}" target="_blank" class="flex items-center gap-3 px-10 py-1 text-slate-500 hover:text-yellow-400 transition-all">
                <i class="bi bi-plus-circle text-[10px]"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Buka Form Tamu</span>
            </a>
        </div>

        {{-- SISTEM WHATSAPP
        <a href="{{ route('admin.whatsapp.index') }}"
            class="flex items-center gap-5 px-7 py-4 rounded-2xl transition-colors duration-200 {{ request()->routeIs('admin.whatsapp.*') ? 'bg-emerald-500 text-white shadow-xl shadow-emerald-500/20' : 'hover:bg-slate-800/50 text-slate-300 hover:text-white' }}">
            <i class="bi bi-whatsapp text-xl"></i>
            <span class="text-[13px] font-black uppercase tracking-wider">Sistem WA</span>
        </a>

    </nav>  --}}

    {{-- KELUAR SISTEM --}}
    <div class="p-6 border-t border-slate-800/50 bg-[#10192A] sticky bottom-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="flex items-center gap-5 px-7 py-4 w-full bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-xl font-black uppercase tracking-wider transition-all duration-200 text-left active:scale-[0.98]">
                <i class="bi bi-door-open text-xl"></i>
                <span class="text-[13px]">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
