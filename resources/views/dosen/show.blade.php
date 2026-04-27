<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Referensi - {{ $buku->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased">

    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dosen.beranda') }}" class="hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-arrow-left-circle text-xl"></i>
                    <span class="text-xs font-black uppercase tracking-widest text-white">Kembali</span>
                </a>
            </div>
            <div class="text-[10px] font-bold uppercase tracking-widest opacity-70">
                Arsip Referensi Prodi {{ Auth::user()->prodi }}
            </div>
        </div>
    </nav>

    <main class="py-12 px-6 max-w-6xl mx-auto">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-white flex flex-col md:flex-row">
            
            {{-- Visual Buku --}}
            <div class="md:w-1/3 bg-slate-50 p-10 flex flex-col items-center justify-center border-r border-slate-100">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-indigo-600 rounded-[2rem] blur opacity-25"></div>
                    <img src="{{ $buku->gambar_buku ? asset('images/' . $buku->gambar_buku) : asset('images/default-cover.jpg') }}" 
                         class="relative w-full max-w-[280px] rounded-[2rem] shadow-2xl transform transition duration-500 group-hover:scale-105">
                </div>
                
                <div class="mt-8">
                    <span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center">
                        <i class="bi bi-patch-check-fill mr-2"></i> Status: Koleksi Aktif
                    </span>
                </div>
            </div>

            {{-- Detail Informasi --}}
            <div class="md:w-2/3 p-12">
                <div class="mb-8">
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em] mb-3">Informasi Metadata Buku</p>
                    <h1 class="text-4xl font-black text-[#2D3E50] leading-tight uppercase tracking-tighter mb-4">
                        {{ $buku->judul }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-slate-500">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-person-circle text-indigo-500"></i>
                            <span class="text-xs font-bold uppercase">{{ $buku->penulis }}</span>
                        </div>
                        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar-event text-indigo-500"></i>
                            <span class="text-xs font-bold uppercase">{{ $buku->tahun_terbit ?? $buku->tahun }}</span>
                        </div>
                    </div>
                </div>

                {{-- Spesifikasi --}}
                <div class="grid grid-cols-2 gap-6 mb-10">
                    <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-2">Penerbit</p>
                        <p class="text-xs font-bold text-[#2D3E50] uppercase">{{ $buku->penerbit ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-2">Kategori Koleksi</p>
                        <p class="text-xs font-bold text-[#2D3E50] uppercase">{{ $buku->kategori ?? 'Umum' }}</p>
                    </div>
                </div>

                {{-- Klaim Relasi --}}
                <div class="mb-10 bg-emerald-50/30 p-8 rounded-[2rem] border border-emerald-100/50">
                    <h4 class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-4 flex items-center">
                        <i class="bi bi-journal-bookmark-fill mr-2"></i> Terklaim Untuk Mata Kuliah:
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @if(isset($claim) && $claim->mata_kuliah)
                            @foreach(explode(',', $claim->mata_kuliah) as $mk)
                                <span class="px-4 py-2 bg-white text-emerald-600 border border-emerald-100 rounded-xl text-[10px] font-black uppercase shadow-sm">
                                    {{ trim($mk) }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-xs italic text-slate-400">Referensi ini bersifat umum untuk Program Studi.</span>
                        @endif
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Terakhir diakses: {{ now()->format('d/m/Y') }}</p>
                    </div>
                    <button onclick="window.print()" class="px-8 py-3 bg-[#2D3E50] text-white rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition shadow-lg">
                        <i class="bi bi-printer mr-2"></i> Cetak Metadata
                    </button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>