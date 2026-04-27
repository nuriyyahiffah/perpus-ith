<aside class="w-72 bg-[#0f172a] text-slate-300 h-screen sticky top-0 overflow-y-auto custom-scrollbar transition-all duration-300 shadow-2xl border-r border-slate-800">
    {{-- Header --}}
    <div class="p-8 mb-4 sticky top-0 z-20 bg-[#0f172a]/95 backdrop-blur-md border-b border-slate-800/50">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="bi bi-book-half text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-white font-black tracking-tighter text-lg leading-tight uppercase">ITH Library</h1>
                <p class="text-[10px] text-blue-400 font-bold uppercase tracking-[0.2em]">Pustakawan Panel</p>
            </div>
        </div>
    </div>

    <nav class="px-4 space-y-2 pb-10">
        {{-- 1. DASHBOARD --}}
        <a href="{{ route('pustakawan.dashboard') }}" 
            class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('pustakawan.dashboard') ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'hover:bg-slate-800/50 hover:text-white' }}">
            <i class="bi bi-grid-1x2-fill text-lg"></i>
            <span class="text-xs font-bold uppercase tracking-widest">1. Dashboard</span>
        </a>

        {{-- 2. MANAJEMEN PENGGUNA --}}
        <div class="menu-item">
            @php $userActive = request()->routeIs('shared.mahasiswa.*', 'shared.dosen.*', 'shared.pegawai.*', 'shared.kategori-anggota.*'); @endphp
            <button onclick="toggleSubmenu('submenu-pengguna', this)" 
                class="w-full flex items-center justify-between px-6 py-4 rounded-2xl transition-all group {{ $userActive ? 'text-blue-400 bg-blue-400/5' : 'hover:bg-slate-800/50' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-people-fill text-lg"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">2. Manajemen Pengguna</span>
                </div>
                <i class="bi bi-chevron-down text-[10px] arrow-icon transition-transform duration-300 {{ $userActive ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="submenu-pengguna" class="{{ $userActive ? '' : 'hidden' }} space-y-1 mt-1 px-2 overflow-hidden">
                <x-sidebar-link href="{{ route('shared.mahasiswa.index') }}" label="Data Mahasiswa" :active="request()->routeIs('shared.mahasiswa.*')" />
                <x-sidebar-link href="{{ route('shared.dosen.index') }}" label="Data Dosen" :active="request()->routeIs('shared.dosen.*')" />
                <x-sidebar-link href="{{ route('shared.pegawai.index') }}" label="Data Pegawai" :active="request()->routeIs('shared.pegawai.*')" />
                <x-sidebar-link href="{{ route('shared.kategori-anggota.index') }}" label="Kategori Anggota" :active="request()->routeIs('shared.kategori-anggota.*')" />
            </div>
        </div>

        {{-- 3. MANAJEMEN BUKU --}}
        <div class="menu-item">
            @php $bukuActive = request()->routeIs('shared.buku.*', 'shared.kategori-buku.*'); @endphp
            <button onclick="toggleSubmenu('submenu-buku', this)" 
                class="w-full flex items-center justify-between px-6 py-4 rounded-2xl transition-all group {{ $bukuActive ? 'text-blue-400 bg-blue-400/5' : 'hover:bg-slate-800/50' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-journal-bookmark-fill text-lg"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">3. Manajemen Buku</span>
                </div>
                <i class="bi bi-chevron-down text-[10px] arrow-icon transition-transform duration-300 {{ $bukuActive ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="submenu-buku" class="{{ $bukuActive ? '' : 'hidden' }} space-y-1 mt-1 px-2 overflow-hidden">
                <x-sidebar-link href="{{ route('shared.buku.index') }}" label="Katalog & Eksemplar" :active="request()->routeIs('shared.buku.*')" />
                <x-sidebar-link href="{{ route('shared.kategori-buku.index') }}" label="Kategori (Prodi)" :active="request()->routeIs('shared.kategori-buku.*')" />
            </div>
        </div>

        {{-- 4. SIRKULASI --}}
        <div class="menu-item">
            @php 
                $sirkulasiActive = request()->routeIs('shared.transaksi.*', 'shared.peminjaman.konfirmasi', 'shared.peminjaman.create', 'shared.anggota.perpanjangan'); 
            @endphp
            <button onclick="toggleSubmenu('submenu-sirkulasi', this)" 
                class="w-full flex items-center justify-between px-6 py-4 rounded-2xl transition-all group {{ $sirkulasiActive ? 'text-blue-400 bg-blue-400/5' : 'hover:bg-slate-800/50' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-arrow-left-right text-lg"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">4. Sirkulasi</span>
                </div>
                <i class="bi bi-chevron-down text-[10px] arrow-icon transition-transform duration-300 {{ $sirkulasiActive ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="submenu-sirkulasi" class="{{ $sirkulasiActive ? '' : 'hidden' }} space-y-1 mt-1 px-2 overflow-hidden">
                <x-sidebar-link href="{{ route('shared.transaksi.index') }}" label="Daftar Sirkulasi" :active="request()->routeIs('shared.transaksi.index')" />
                <x-sidebar-link href="{{ route('shared.peminjaman.konfirmasi') }}" label="Konfirmasi Klaim Dosen" :active="request()->routeIs('shared.peminjaman.konfirmasi')" />
                <x-sidebar-link href="{{ route('shared.peminjaman.create') }}" label="Input Pinjaman Baru" :active="request()->routeIs('shared.peminjaman.create')" />
            </div>
        </div>

        {{-- 5. USULAN BUKU --}}
        <div class="menu-item">
            @php $usulanActive = request()->routeIs('shared.usulan.index'); @endphp
            <a href="{{ route('shared.usulan.index') }}" 
               class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all duration-300 {{ $usulanActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'hover:bg-slate-800/50 hover:text-white' }}">
                <i class="bi bi-lightbulb-fill text-lg"></i>
                <span class="text-xs font-bold uppercase tracking-widest">5. Usulan Buku</span>
            </a>
        </div>

        {{-- 6. PENGUMUMAN --}}
        <div class="menu-item">
            @php $pengumumanActive = request()->routeIs('pustakawan.pengumuman.index'); @endphp
            <a href="{{ route('pustakawan.pengumuman.index') }}" 
               class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all duration-300 {{ $pengumumanActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'hover:bg-slate-800/50 hover:text-white' }}">
                <i class="bi bi-megaphone-fill text-lg"></i>
                <span class="text-xs font-bold uppercase tracking-widest">6. Pengumuman</span>
            </a>
        </div>

        {{-- 7. LAPORAN BULANAN --}}
        <div class="menu-item">
            @php $bulanActive = request()->routeIs('shared.laporan.bulanan'); @endphp
            <a href="{{ route('shared.laporan.bulanan') }}" 
               class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all duration-300 {{ $bulanActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'hover:bg-slate-800/50 hover:text-white' }}">
                <i class="bi bi-calendar-range-fill text-lg"></i>
                <span class="text-xs font-bold uppercase tracking-widest">8. Laporan Bulanan</span>
            </a>
        </div>

        {{-- 8. BUKU TAMU --}}
        <div class="menu-item space-y-2">
            @php $bukuTamuActive = request()->routeIs('shared.buku-tamu.*'); @endphp
            <a href="{{ route('shared.buku-tamu.index') }}" 
               class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all duration-300 {{ $bukuTamuActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'hover:bg-slate-800/50 hover:text-white' }}">
                <i class="bi bi-person-lines-fill text-lg"></i>
                <span class="text-xs font-bold uppercase tracking-widest">8. Buku Tamu</span>
            </a>
            <a href="{{ route('buku-tamu.create') }}" 
               target="_blank"
               class="flex items-center gap-3 px-8 py-2 text-slate-400 hover:text-blue-400 transition-all duration-300">
                <i class="bi bi-plus-circle text-sm"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Buka Form Tamu</span>
            </a>
        </div>

        {{-- LOGOUT --}}
        <div class="pt-6 border-t border-slate-800/50 mt-6">
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-6 py-4 bg-red-500/10 text-red-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all duration-300 group">
                    <i class="bi bi-box-arrow-right text-lg group-hover:scale-110 transition-transform"></i>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </nav>
</aside>

{{-- SCRIPT AGAR DROPDOWN BISA DIKLIK --}}
<script>
    function toggleSubmenu(id, button) {
        const submenu = document.getElementById(id);
        const icon = button.querySelector('.bi-chevron-down');
        
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>