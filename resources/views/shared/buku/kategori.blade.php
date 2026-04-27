<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Koleksi - Admin SIPUSTAKA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,800;1,200;1,800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] flex h-screen overflow-hidden text-slate-800">

     @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

    <aside class="w-72 bg-[#1E293B] h-full flex flex-col p-6 shadow-2xl overflow-y-auto custom-scrollbar">
        <div class="flex items-center gap-3 px-2 mb-10">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="bi bi-book-half text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-white font-[900] italic uppercase tracking-tighter leading-none text-lg">Sipustaka</h1>
                <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 hover:text-white transition">
                <i class="bi bi-grid-1x2-fill text-lg text-white/50"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Dashboard</span>
            </a>

            <div class="mt-8 px-4 py-2 text-[10px] font-black uppercase text-slate-500 tracking-widest italic">Katalog Pustaka</div>

            <a href="{{ route('admin.buku.index') }}" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 hover:text-white transition">
                <i class="bi bi-journal-bookmark-fill text-lg group-hover:text-blue-400 transition"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Data Buku</span>
            </a>

            <a href="{{ route('admin.kategori-buku.index') }}" class="flex items-center gap-3 p-4 rounded-2xl bg-white/10 text-white shadow-xl shadow-black/5 transition">
                <i class="bi bi-tags-fill text-blue-400 text-lg"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Kategori Buku</span>
            </a>

            <div class="mt-8 px-4 py-2 text-[10px] font-black uppercase text-slate-500 tracking-widest italic">Sirkulasi</div>

            <a href="{{ route('admin.transaksi.index') }}" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 hover:text-white transition group">
                <i class="bi bi-arrow-left-right text-lg group-hover:text-blue-400 transition"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Transaksi Pinjam</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
        <div class="max-w-7xl mx-auto space-y-10">

            <div class="flex justify-between items-end">
                <div>
                    <h1 class="text-4xl font-[900] uppercase italic tracking-tighter text-blue-600">GALERI KATEGORI</h1>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-2 italic">Monitoring Koleksi Per Kategori</p>
                </div>
                <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-[#1E293B] text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition shadow-xl active:scale-95">
                    + Kelola Kategori
                </button>
            </div>

            @forelse($kategoris as $kategori)
            <div class="bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-50 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <i class="bi bi-tag-fill text-xl"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase italic tracking-tight">{{ $kategori->nama }}</h2>
                        <span class="px-4 py-1 bg-slate-100 rounded-full text-[9px] font-black text-slate-400 uppercase tracking-widest">Total: {{ $kategori->bukus->count() }} Buku</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @forelse($kategori->bukus as $buku)
                    <div class="group">
                        <div class="aspect-[3/4] rounded-[2rem] overflow-hidden mb-3 shadow-md bg-slate-50 border border-slate-100 relative">
                            <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" onerror="this.src='https://via.placeholder.com/300x400?text=No+Cover'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <p class="text-[9px] text-white font-bold uppercase tracking-widest leading-tight">{{ $buku->judul }}</p>
                            </div>
                        </div>
                        <h3 class="font-black text-[10px] uppercase italic text-slate-700 truncate px-2">{{ $buku->judul }}</h3>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-slate-300 italic text-[10px] font-bold uppercase tracking-widest">
                        Belum ada koleksi buku di kategori ini
                    </div>
                    @endforelse
                </div>
            </div>
            @empty
            <div class="text-center py-20">
                <p class="text-slate-300 font-black uppercase tracking-widest italic">Belum ada kategori yang dibuat</p>
            </div>
            @endforelse
        </div>
    </main>

    <div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-6">
        </div>

</body>
</html>
