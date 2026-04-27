<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku->judul }} - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased text-slate-800">

    {{-- Navbar --}}
    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-8">
                <span class="text-[10px] font-bold uppercase tracking-wider">SIPUSTAKA <span class="text-yellow-400">Digital Library</span></span>
            </div>
            
            @php
                $backUrl = url()->previous();
                if($backUrl == url()->current()) {
                    if(Auth::user()->role == 'admin') $backUrl = route('admin.dashboard');
                    elseif(Auth::user()->role == 'mahasiswa') $backUrl = route('mahasiswa.beranda');
                    elseif(Auth::user()->role == 'dosen') $backUrl = route('dosen.beranda');
                }
            @endphp
            <a href="{{ $backUrl }}" class="text-[10px] font-bold uppercase hover:text-yellow-400 transition flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-50 flex flex-col md:flex-row">
                
                {{-- Bagian Kiri: Cover --}}
                <div class="md:w-[40%] bg-slate-50 p-12 flex flex-col items-center justify-center border-r border-slate-100">
                    <div class="relative group">
                        @if($buku->gambar_buku)
                            <img src="{{ asset('images/' . $buku->gambar_buku) }}" alt="{{ $buku->judul }}" 
                                 class="w-64 shadow-[0_20px_50px_rgba(0,0,0,0.2)] rounded-2xl transform group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-64 aspect-[3/4] bg-slate-200 rounded-2xl flex flex-col items-center justify-center text-slate-400 border-2 border-dashed border-slate-300">
                                <i class="bi bi-book text-6xl mb-4"></i>
                                <span class="text-[10px] font-bold uppercase">Sampul Tidak Tersedia</span>
                            </div>
                        @endif
                        <div class="absolute -bottom-4 -right-4 bg-yellow-400 text-[#2D3E50] w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg font-black text-xs">
                            {{ $buku->tahun_terbit ?? '2026' }}
                        </div>
                    </div>
                </div>

                {{-- Bagian Kanan: Detail --}}
                <div class="md:w-[60%] p-10 md:p-16">
                    <div class="mb-10">
                        <span class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                            {{ $buku->kategori->nama_kategori ?? 'Koleksi Umum' }}
                        </span>
                        <h1 class="text-4xl font-black text-[#2D3E50] uppercase tracking-tight mt-6 leading-tight">
                            {{ $buku->judul }}
                        </h1>
                        <p class="text-slate-400 text-sm font-bold mt-3 flex items-center gap-2 uppercase tracking-widest">
                            <i class="bi bi-person-fill text-yellow-500"></i> {{ $buku->penulis }}
                        </p>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 gap-6 py-8 border-y border-slate-100 mb-10">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Penerbit</h4>
                            <p class="text-sm font-bold text-[#2D3E50]">{{ $buku->penerbit ?? 'ITH Collection' }}</p>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Sisa</h4>
                            <p class="text-sm font-bold {{ $buku->stok > 0 ? 'text-emerald-500' : 'text-red-500' }}">
                                {{ $buku->stok ?? '0' }} Eksemplar
                            </p>
                        </div>
                    </div>

                   {{-- Sinopsis --}}
<div class="mb-12">
    <h4 class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
        <i class="bi bi-info-circle text-indigo-500"></i> Sinopsis Singkat
    </h4>
    
    <div class="text-slate-600 leading-relaxed text-sm text-justify">
        @if($buku->sinopsis && trim($buku->sinopsis) !== "")
            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                {!! nl2br(e($buku->sinopsis)) !!}
            </div>
        @else
            <div class="flex items-center gap-3 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <i class="bi bi-chat-left-dots text-slate-300 text-xl"></i>
                <span class="italic text-slate-400 text-xs">Informasi sinopsis untuk buku ini belum tersedia di sistem.</span>
            </div>
        @endif
    </div>
</div>

                    {{-- Area Tombol Aksi --}}
                    <div class="flex flex-col gap-4">
                        
                        {{-- MAHASISWA: INFO LOKET --}}
                        @if(Auth::user()->role == 'mahasiswa')
                            <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-100">
                                    <i class="bi bi-shop"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-wider">Prosedur Perpustakaan</p>
                                    <p class="text-[11px] text-emerald-700 font-medium">Silakan kunjungi loket petugas untuk memproses peminjaman buku ini secara fisik.</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-4">
                            {{-- DOSEN: Tombol Usulan --}}
                            @if(Auth::user()->role == 'dosen')
                                <button class="flex-[2] py-5 bg-[#2D3E50] text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
                                    <i class="bi bi-journal-plus me-2"></i> Usulkan Referensi
                                </button>
                            @endif

                            {{-- Tombol Bookmark --}}
                            <button class="flex-1 py-5 border-2 border-slate-100 text-slate-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition flex items-center justify-center">
                                <i class="bi bi-bookmark me-2"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>