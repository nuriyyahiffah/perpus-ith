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

    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-8">
                <span class="text-[10px] font-bold uppercase tracking-wider">Digital <span class="text-yellow-400">Library ITH</span></span>
            </div>
            <a href="{{ route('dosen.beranda') }}" class="text-[10px] font-bold uppercase hover:text-yellow-400 transition">Kembali</a>
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

                        {{-- Penerbit (BARU) --}}
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