<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usul Buku Baru - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased">

    {{-- Navbar Seragam Sesuai Referensi Gambar --}}
    <nav class="bg-[#2D3E50] text-white py-4 px-6 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">

            {{-- Sisi Kiri: Tombol Kembali, Logo, dan Nama Instansi --}}
            <div class="flex items-center space-x-5">
                {{-- Tombol Kembali mengarah ke Beranda Dosen --}}
                <a href="{{ route('dosen.beranda') }}" class="text-white hover:text-slate-300 transition text-xl flex items-center">
                    <i class="bi bi-arrow-left text-2xl font-bold"></i>
                </a>

                {{-- Garis Pembatas Vertikal Pertama --}}
                <div class="h-8 w-[1px] bg-slate-500/40"></div>

                {{-- Logo dan Teks Instansi Perpustakaan --}}
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-9">

                    {{-- Garis Pembatas Vertikal Kedua --}}
                    <div class="h-8 w-[1px] bg-slate-500/40 mx-1"></div>

                    <div class="flex flex-col">
                        <span class="text-xs font-black uppercase tracking-wider leading-none">PERPUSTAKAAN</span>
                        <span class="text-[8px] text-yellow-400 font-bold uppercase tracking-wider mt-1">Institut Teknologi Bacharuddin Jusuf Habibie</span>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Informasi Dosen yang Aktif --}}
            <div class="flex flex-col text-right">
                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Pengguna Aktif</span>
                <span class="text-xs font-bold text-white tracking-wide leading-none">{{ Auth::user()->name }}</span>
            </div>

        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-3xl mx-auto">
            {{-- Alert Success/Error --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold uppercase tracking-wider">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8">
                <h1 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tight">Usul Buku Baru</h1>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Lengkapi data buku untuk pengadaan koleksi baru</p>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-100">
                <div class="p-1 bg-gradient-to-r from-yellow-400 to-amber-500"></div>

                <form action="{{ route('dosen.usulan.store') }}" method="POST" class="p-10 space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Judul Buku --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Judul Lengkap Buku</label>
                            <input type="text" name="judul" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-yellow-500 transition text-sm font-semibold" placeholder="Contoh: Laravel Web Development">
                        </div>

                        {{-- Penulis --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Penulis / Pengarang</label>
                            <input type="text" name="penulis" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-yellow-500 transition text-sm font-semibold" placeholder="Nama Lengkap Penulis">
                        </div>

                        {{-- Penerbit --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Penerbit</label>
                            <input type="text" name="penerbit" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-yellow-500 transition text-sm font-semibold" placeholder="Contoh: Andi Offset">
                        </div>

                        {{-- Tahun Terbit --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Tahun Terbit</label>
                            <input type="number" name="tahun" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-yellow-500 transition text-sm font-semibold" placeholder="2024">
                        </div>
                    </div>

                    {{-- Alasan --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Alasan Pengusulan</label>
                        <textarea name="alasan" rows="4" class="w-full px-5 py-4 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-yellow-500 transition text-sm font-semibold" placeholder="Jelaskan mengapa buku ini perlu ada di perpustakaan..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#2D3E50] text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:bg-slate-800 transition transform active:scale-[0.98]">
                        Kirim Usulan <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
