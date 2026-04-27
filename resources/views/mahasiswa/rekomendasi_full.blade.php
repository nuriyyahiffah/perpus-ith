<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Rekomendasi Prodi {{ Auth::user()->prodi }} - SIPUSTAKA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    {{-- Navigation --}}
    <nav class="bg-[#2D3E50] text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('mahasiswa.beranda') }}" class="hover:text-yellow-400 transition">
                    <i class="bi bi-arrow-left-circle text-xl"></i>
                </a>
                <span class="text-sm font-black uppercase tracking-widest">Rekomendasi <span class="text-yellow-400">Prodi</span></span>
            </div>
            <div class="text-[10px] font-bold uppercase tracking-widest opacity-70">
                {{ Auth::user()->prodi }}
            </div>
        </div>
    </nav>

    <main class="py-12 px-6 max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-12 text-center">
            <h1 class="text-3xl font-black text-[#2D3E50] uppercase tracking-tighter mb-3">
                Koleksi Spesifik Prodi <span class="text-emerald-500">{{ Auth::user()->prodi }}</span>
            </h1>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-[0.2em]">
                Daftar lengkap buku yang diklaim oleh Kaprodi untuk kebutuhan akademik Anda
            </p>
            <div class="w-24 h-1 bg-emerald-500 mx-auto mt-6 rounded-full"></div>
        </div>

        {{-- Grid System --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($bukuRekomendasi as $item)
            <div class="bg-white rounded-[2.5rem] p-5 shadow-xl shadow-slate-200/50 border border-white group relative flex flex-col transition-all duration-300 hover:-translate-y-2">
                
                {{-- Container Gambar & Hover Effect --}}
                <div class="aspect-[3/4] rounded-3xl bg-slate-100 mb-5 overflow-hidden relative shadow-inner">
                    <img src="{{ $item->buku && $item->buku->gambar_buku ? asset('images/' . $item->buku->gambar_buku) : asset('images/default-cover.jpg') }}" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    @if($item->buku)
                    <div class="absolute inset-0 bg-[#2D3E50]/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <a href="{{ route('mahasiswa.buku.show', $item->buku->id) }}" class="bg-white text-[#2D3E50] px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all">
                            Lihat Detail
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Detail Info --}}
                <div class="flex-grow">
                    <h3 class="font-black text-[#2D3E50] text-sm uppercase leading-tight mb-2 min-h-[40px] line-clamp-2">
                        {{ $item->buku?->judul ?? 'Judul Tidak Tersedia' }}
                    </h3>
                    
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 bg-slate-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-person text-[10px] text-slate-400"></i>
                        </div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">
                            {{ $item->buku?->penulis ?? 'Anonim' }}
                        </p>
                    </div>

                    <hr class="border-slate-50 mb-4">

                    {{-- List Mata Kuliah Section --}}
                    <div class="mb-4">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center">
                            <i class="bi bi-journal-bookmark-fill mr-1.5 text-emerald-500"></i> Referensi Mata Kuliah:
                        </p>
                            
                        <div class="flex flex-wrap gap-2">
                            @if($item->mata_kuliah)
                                @php $list_mk = explode(',', $item->mata_kuliah); @endphp
                                @foreach($list_mk as $mk)
                                    @if(trim($mk) !== "")
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[8px] font-black uppercase tracking-wider flex items-center shadow-sm">
                                        <i class="bi bi-tag-fill mr-1.5 opacity-50"></i> {{ trim($mk) }}
                                    </span>
                                    @endif
                                @endforeach
                            @else
                                <span class="px-3 py-1 bg-slate-50 text-slate-400 border border-slate-100 rounded-lg text-[8px] font-black uppercase tracking-wider italic">
                                    Umum / Dasar
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Footer Card --}}
                <div class="mt-auto pt-4 border-t border-slate-50 flex justify-between items-center">
                    <span class="text-[9px] font-black text-slate-300 uppercase italic">
                        Ref: {{ $item->buku?->tahun_terbit ?? ($item->buku?->tahun ?? 'N/A') }}
                    </span>
                    <div class="flex gap-1">
                        <i class="bi bi-star-fill text-yellow-400 text-[10px]"></i>
                        <i class="bi bi-star-fill text-yellow-400 text-[10px]"></i>
                        <i class="bi bi-star-fill text-yellow-400 text-[10px]"></i>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-24 text-center">
                <div class="bg-white inline-block p-12 rounded-[3rem] shadow-sm border border-slate-100">
                    <i class="bi bi-folder-x text-6xl text-slate-100 mb-4 block"></i>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-[0.3em]">Belum Ada Data Rekomendasi</h3>
                    <p class="text-slate-300 text-[9px] mt-2 italic uppercase">Silakan hubungi Kaprodi {{ Auth::user()->prodi }} untuk pengajuan buku</p>
                </div>
            </div>
            @endforelse
        </div>
    </main>

    <footer class="py-12 bg-white border-t border-slate-100 mt-20">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.3em] mb-2">SIPUSTAKA DIGITAL LIBRARY</p>
            <p class="text-[8px] text-slate-400 font-bold uppercase">Institut Teknologi Bacharuddin Jusuf Habibie (ITH)</p>
        </div>
    </footer>

</body>
</html>