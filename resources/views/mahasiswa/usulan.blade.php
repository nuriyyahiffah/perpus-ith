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

    {{-- MEMANGGIL HEADER SERAGAM TERPUSAT --}}
    @include('layouts.header')

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
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Sarankan buku untuk menunjang perkuliahan Anda di Prodi <strong>{{ Auth::user()->prodi }}</strong></p>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-100">
                <div class="p-1 bg-gradient-to-r from-indigo-400 to-blue-500"></div>

                <form action="{{ route('mahasiswa.usulan.store') }}" method="POST" class="p-10 space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Judul Buku --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Judul Lengkap Buku</label>
                            <input type="text" name="judul" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition text-sm font-semibold" placeholder="Contoh: Algoritma Pemrograman">
                            @error('judul')
                                <span class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Penulis --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Penulis / Pengarang</label>
                            <input type="text" name="penulis" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition text-sm font-semibold" placeholder="Nama Lengkap Penulis">
                            @error('penulis')
                                <span class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Penerbit --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Penerbit</label>
                            <input type="text" name="penerbit" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition text-sm font-semibold" placeholder="Contoh: Andi Offset">
                            @error('penerbit')
                                <span class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tahun Terbit --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Tahun Terbit</label>
                            <input type="number" name="tahun" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition text-sm font-semibold" placeholder="2024">
                            @error('tahun')
                                <span class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Alasan --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Alasan / Mata Kuliah yang Membutuhkan</label>
                        <textarea name="alasan" rows="4" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-indigo-500 transition text-sm font-semibold" placeholder="Jelaskan mengapa buku ini diperlukan dan untuk mata kuliah apa..."></textarea>
                        @error('alasan')
                            <span class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:bg-indigo-700 transition transform active:scale-[0.98]">
                        Kirim Usulan <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
