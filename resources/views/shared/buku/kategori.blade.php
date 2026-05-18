<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Koleksi - Admin SIPUSTAKA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false
            })
        })
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased text-slate-800" x-data="">

    <div class="flex h-screen w-screen overflow-hidden relative">
        
        {{-- 1. PANGGIL SIDEBAR DINAMIS --}}
        @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

        {{-- 2. KONTEN UTAMA DASHBOARD (Scrollable Mandiri) --}}
        <main class="flex-1 h-full overflow-y-auto p-6 md:p-10 custom-scrollbar bg-[#F8FAFC]">
            <div class="max-w-7xl mx-auto space-y-8">

                {{-- Header Atas (Responsif Hamburger + Judul Halaman) --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6">
                    <div class="flex items-center gap-4">
                        <button @click="$store.sidebar.open = !$store.sidebar.open" 
                                class="lg:hidden p-3 bg-white text-slate-700 hover:text-blue-600 rounded-2xl shadow-sm border border-slate-200/60 transition active:scale-95 flex items-center justify-center shrink-0">
                            <i class="bi bi-list text-xl leading-none"></i>
                        </button>
                        
                        <div>
                            <h1 class="text-2xl md:text-3xl font-[900] uppercase italic tracking-tighter text-blue-600 leading-none">GALERI KATEGORI</h1>
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-2 italic">Monitoring Koleksi Per Kategori</p>
                        </div>
                    </div>
                    
                    {{-- Tombol Aksi Kelola Input --}}
                    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-[#1E293B] hover:bg-blue-600 text-white px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition shadow-md active:scale-95 flex items-center gap-2">
                        <i class="bi bi-sliders"></i> Kelola Kategori
                    </button>
                </div>

                {{-- Komponen Tampilan Galeri Visual Berdasarkan Kategori --}}
                <div class="space-y-8">
                    @forelse($kategoris as $kategori)
                    <div class="bg-white rounded-[2.5rem] p-6 md:p-8 border border-slate-100 shadow-sm space-y-6 transition hover:shadow-md">
                        
                        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shadow-inner">
                                    <i class="bi bi-tag-fill text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-extrabold uppercase italic tracking-tight text-slate-800 leading-tight">{{ $kategori->nama }}</h2>
                                    <span class="inline-block mt-1 px-2.5 py-0.5 bg-slate-100 rounded-full text-[9px] font-black text-slate-400 uppercase tracking-wider">
                                        Total: {{ $kategori->bukus->count() }} Buku
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                            @forelse($kategori->bukus as $buku)
                            <div class="group cursor-pointer">
                                <div class="aspect-[3/4] rounded-2xl overflow-hidden mb-2.5 shadow-sm bg-slate-50 border border-slate-100/80 relative">
                                    <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" onerror="this.src='https://via.placeholder.com/300x400?text=No+Cover'">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                                        <p class="text-[9px] text-white font-bold uppercase tracking-wide leading-tight line-clamp-2">{{ $buku->judul }}</p>
                                    </div>
                                </div>
                                <h3 class="font-bold text-[10px] uppercase text-slate-600 truncate px-1 group-hover:text-blue-600 transition">{{ $buku->judul }}</h3>
                            </div>
                            @empty
                            <div class="col-span-full py-8 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 text-slate-400 italic text-[10px] font-bold uppercase tracking-widest">
                                <i class="bi bi-inboxes text-lg block mb-1 text-slate-300"></i>
                                Belum ada koleksi buku di kategori ini
                            </div>
                            @endforelse
                        </div>

                    </div>
                    @empty
                    <div class="text-center py-20 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm">
                        <i class="bi bi-tags text-4xl text-slate-300 block mb-3"></i>
                        <p class="text-slate-400 font-black uppercase tracking-widest italic text-xs">Belum ada data kategori yang terdaftar</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>

    {{-- MODAL BOX: Tempat Mengelola/Input Tambah Kategori (Ganti ID/Form Sesuai Controller Anda) --}}
    <div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-lg p-6 md:p-8 shadow-2xl border border-slate-100 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="text-lg font-black uppercase italic tracking-tight text-slate-800">Kelola Master Kategori</h3>
                <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition text-xl"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <p class="text-xs text-slate-500">Anda dapat memindahkan elemen form isian input kategori lama ke dalam modal popupbox aman ini agar porsi halaman utama murni menjadi galeri pantauan visual.</p>
        </div>
    </div>

</body>
</html>