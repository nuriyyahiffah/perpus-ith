<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjaman Saya - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased">

    {{-- Header Sederhana --}}
    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="{{ route('mahasiswa.beranda') }}" class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
            <span class="text-[10px] font-black uppercase tracking-[0.3em]">Pinjaman Aktif</span>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-black text-[#2D3E50] uppercase tracking-tighter">Pinjaman <span class="text-indigo-600">Saya</span></h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Daftar buku yang sedang kamu pinjam saat ini</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($pinjaman as $item)
            <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white flex flex-col md:flex-row items-center gap-8 transition-transform hover:-translate-y-1">
                {{-- Cover --}}
                <div class="w-32 h-44 flex-shrink-0 rounded-2xl overflow-hidden shadow-lg">
                    <img src="{{ $item->buku->gambar_buku ? asset('images/' . $item->buku->gambar_buku) : asset('images/default-cover.jpg') }}" class="w-full h-full object-cover">
                </div>

                {{-- Detail --}}
                <div class="flex-grow text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[8px] font-black uppercase">Aktif</span>
                        <span class="text-[9px] text-slate-400 font-bold">No. Induk: {{ $item->eksemplar->no_induk }}</span>
                    </div>
                    <h2 class="text-xl font-black text-[#2D3E50] uppercase leading-tight mb-2">{{ $item->buku->judul }}</h2>
                    <p class="text-sm text-slate-500 font-medium mb-4">Penulis: {{ $item->buku->penulis }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 max-w-sm">
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Tgl Pinjam</p>
                            <p class="text-[11px] font-bold text-[#2D3E50]">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}</p>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-2xl border border-rose-100">
                            <p class="text-[8px] font-black text-rose-400 uppercase mb-1">Batas Kembali</p>
                            <p class="text-[11px] font-bold text-rose-600">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 flex flex-col items-center justify-center border border-emerald-100">
                        <i class="bi bi-check2-all text-emerald-500 text-xl"></i>
                        <span class="text-[7px] font-black text-emerald-600 uppercase">Aman</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                <i class="bi bi-journal-x text-4xl text-slate-300 mb-4 block"></i>
                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Kamu tidak memiliki pinjaman aktif saat ini.</p>
                <a href="{{ route('mahasiswa.beranda') }}" class="mt-6 inline-block px-8 py-3 bg-[#2D3E50] text-white text-[10px] font-bold uppercase rounded-xl hover:bg-indigo-600 transition-all">Cari Buku Sekarang</a>
            </div>
            @endforelse
        </div>
    </main>

    <footer class="py-12 text-center">
        <p class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.3em]">SIPUSTAKA DIGITAL LIBRARY</p>
    </footer>

</body>
</html>