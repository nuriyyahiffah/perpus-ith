<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog SIPUSTAKA - Perpustakaan ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .card-hover:hover { transform: translateY(-5px); }
        .glass-nav { background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="antialiased">

    <nav class="glass-nav sticky top-0 z-50 border-b border-white/10 text-white py-3 shadow-xl shadow-slate-900/10">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-11 w-auto drop-shadow-md">
                </div>
                
                <div class="leading-none border-l border-white/10 pl-4">
                    <span class="font-black italic uppercase tracking-tighter text-xl block">SIPUSTAKA</span>
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em]">Institut Teknologi Bacharuddin Jusuf Habibie</span>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                @auth
                    <div class="hidden md:flex flex-col items-end leading-none">
                        <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Pengguna aktif</span>
                        <span class="text-xs font-bold text-yellow-400 italic">{{ Auth::user()->name }}</span>
                    </div>
                    
                    @php
                        $dashRoute = 'beranda';
                        if(Auth::check()) {
                            $role = Auth::user()->role;
                            if($role === 'admin') $dashRoute = 'admin.dashboard';
                            elseif($role === 'pustakawan') $dashRoute = 'pustakawan.dashboard';
                            elseif($role === 'dosen') $dashRoute = 'dosen.beranda';
                            elseif($role === 'mahasiswa') $dashRoute = 'mahasiswa.beranda';
                        }
                    @endphp
                    
                    <a href="{{ route($dashRoute) }}" class="bg-white/10 hover:bg-white/20 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border border-white/10 active:scale-95">
                        <i class="bi bi-grid-fill mr-2"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-500 transition shadow-lg shadow-blue-500/20 active:scale-95">Masuk Akun</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="bg-white border-b border-slate-100 py-8 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-[900] text-[#1E293B] italic uppercase tracking-tighter">
                    Katalog <span class="text-blue-600">Buku</span>
                </h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Eksplorasi referensi akademik digital</p>
            </div>
            <div class="h-1.5 w-12 bg-yellow-400 rounded-full hidden md:block"></div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 py-10">
        
        <form action="{{ route('katalog.index') }}" method="GET" class="space-y-6">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative group">
                    <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari judul buku, penulis, atau kompetensi..." 
                        class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-50 transition-all shadow-sm">
                </div>
                
                <select name="sort" onchange="this.form.submit()" 
                    class="px-6 py-4 bg-white border border-slate-200 rounded-2xl text-[11px] font-black uppercase tracking-wider text-slate-600 outline-none cursor-pointer shadow-sm appearance-none">
                    <option value="">Urutan</option>
                    <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>A - Z</option>
                    <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Z - A</option>
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                </select>

                <button type="submit" class="px-10 py-4 bg-[#1E293B] text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-blue-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                    Cari
                </button>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <i class="bi bi-funnel text-blue-600"></i>
                    <h3 class="text-[9px] font-black uppercase text-slate-400 tracking-[0.2em]">Filter Program Studi</h3>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    @foreach($listProdi as $p)
                        <label class="cursor-pointer group">
                            <input type="checkbox" name="prodi[]" value="{{ $p }}" class="hidden peer" onchange="this.form.submit()" {{ is_array(request('prodi')) && in_array($p, request('prodi')) ? 'checked' : '' }}>
                            <span class="px-5 py-2.5 rounded-xl border border-slate-100 bg-slate-50 text-[10px] font-bold uppercase text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all inline-block hover:bg-slate-100 italic">
                                {{ $p }}
                            </span>
                        </label>
                    @endforeach
                </div>

                @if(request('prodi') || request('search'))
                    <a href="{{ route('katalog.index') }}" class="inline-block mt-5 text-[9px] font-black text-rose-500 uppercase tracking-widest hover:underline">
                        <i class="bi bi-x-circle mr-1"></i> Reset Filter
                    </a>
                @endif
            </div>
        </form>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-12">
            @forelse($buku as $item)
                <div class="bg-white p-4 rounded-[2rem] border border-slate-100 shadow-sm card-hover transition-all duration-300 group">
                    <div class="aspect-[3/4] bg-slate-50 rounded-2xl mb-4 overflow-hidden flex items-center justify-center relative border border-slate-50">
                        @if($item->cover)
                            <img src="{{ asset('storage/'.$item->cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @elseif($item->gambar_buku)
                            <img src="{{ asset('images/' . $item->gambar_buku) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="text-center">
                                <i class="bi bi-book-half text-4xl text-slate-200"></i>
                                <p class="text-[8px] font-bold text-slate-300 uppercase mt-2">No Cover</p>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-lg shadow-sm border border-slate-100">
                            <span class="text-[9px] font-black text-blue-600 italic leading-none">{{ $item->tahun }}</span>
                        </div>
                    </div>
                    
                    <div class="px-1">
                        <h3 class="text-xs font-black text-slate-800 uppercase leading-snug line-clamp-2 italic mb-1 min-h-[2.5rem] group-hover:text-blue-600 transition">
                            {{ $item->judul }}
                        </h3>
                        
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter truncate mb-5">
                            <span class="text-blue-500 mr-1 italic font-medium">By</span> {{ $item->penulis }}
                        </p>

                        <a href="{{ route('buku.detail', $item->id) }}" class="w-full py-3.5 bg-slate-100 text-slate-600 group-hover:bg-[#1E293B] group-hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all block text-center active:scale-95 shadow-sm">
                            Detail Buku
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
                    <i class="bi bi-search text-5xl text-slate-200 mb-4 block"></i>
                    <p class="text-slate-400 font-black italic uppercase tracking-[0.3em] text-[10px]">Oopss! Koleksi tidak ditemukan</p>
                </div>
            @endforelse
        </div>

        <div class="mt-20 flex justify-center">
            {{ $buku->appends(request()->query())->links() }}
        </div>

        @guest
            <div class="mt-24 p-12 bg-[#1E293B] rounded-[3.5rem] text-center text-white relative overflow-hidden shadow-2xl">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-4">Ingin Akses Penuh?</h2>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mb-8 max-w-lg mx-auto leading-relaxed">
                        Masuk sebagai civitas akademika untuk melihat status ketersediaan buku secara real-time dan melakukan peminjaman digital.
                    </p>
                    <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-12 py-5 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-white hover:text-[#1E293B] transition-all active:scale-95 shadow-xl shadow-blue-500/20">
                        Masuk ke Akun Saya
                    </a>
                </div>
                <i class="bi bi-journals absolute -bottom-10 -right-10 text-[15rem] text-white/5 pointer-events-none transform rotate-12"></i>
            </div>
        @endguest

    </main>

    <footer class="py-16 text-center border-t border-slate-100 bg-white">
        <div class="flex items-center justify-center gap-4 mb-6 opacity-30 grayscale">
             <img src="{{ asset('images/logo_ith.png') }}" class="h-8">
             <div class="h-4 w-px bg-slate-400"></div>
             <span class="font-black italic text-sm text-slate-600 uppercase tracking-tighter">Perpustakaan ITH</span>
        </div>
        <p class="text-[8px] font-black text-slate-300 uppercase tracking-[0.5em] italic">
            &copy; 2026 SIPUSTAKA • INSTITUT TEKNOLOGI BJ HABIBIE
        </p>
    </footer>

</body>
</html>