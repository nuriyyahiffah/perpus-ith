<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Katalog - {{ $buku->judul }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>

<body class="bg-[#F1F5F9] antialiased" 
    x-data="{ 
        judul: '{{ addslashes($buku->judul) }}', 
        penulis: '{{ addslashes($buku->penulis) }}', 
        klasifikasi: '{{ $buku->klasifikasi }}',
        get noPanggil() {
            if(!this.klasifikasi || !this.penulis || !this.judul) return '{{ $buku->no_panggil }}';
            let pnl = this.penulis.trim().substring(0, 3).toUpperCase();
            let jdl = this.judul.trim().charAt(0).toLowerCase();
            return `${this.klasifikasi} ${pnl} ${jdl}`;
        }
    }">

    <div class="flex min-h-screen">
        @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <header class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                        Edit <span class="text-blue-600">Katalog</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-2 font-semibold italic uppercase tracking-wider">Update Data: {{ $buku->judul }}</p>
                </div>
                
                <a href="{{ route('shared.buku.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 text-slate-400 px-6 py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all hover:text-red-500 active:scale-95 shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </header>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <form action="{{ route('shared.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')
                    
                    {{-- Judul & ISBN --}}
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Informasi Dasar</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <input type="text" name="judul" x-model="judul" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                            </div>
                            <div>
                                <input type="text" name="isbn" value="{{ $buku->isbn }}" placeholder="ISBN" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- No Panggil Preview --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="space-y-4 bg-blue-50/30 p-6 rounded-[2rem] border border-blue-100">
                            <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest ml-1 italic">Pengarang</label>
                            <input type="text" name="penulis" x-model="penulis" required class="w-full bg-white border border-slate-200 rounded-xl py-3.5 px-5 text-xs font-bold text-slate-700 outline-none">
                        </div>
                        <div class="p-5 bg-blue-600 rounded-[2rem] shadow-xl shadow-blue-500/20 transition-all">
                            <p class="text-[8px] font-black text-blue-100 uppercase mb-1 italic">Preview No. Panggil Baru:</p>
                            <p class="text-2xl font-[900] text-white italic tracking-tight" x-text="noPanggil"></p>
                            <input type="hidden" name="no_panggil" :value="noPanggil">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Klasifikasi & Cover</label>
                            <input type="text" name="klasifikasi" x-model="klasifikasi" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none">
                            
                            <div class="mt-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Cover Saat Ini:</p>
                                @if($buku->gambar_buku)
                                    <img src="{{ asset('images/'.$buku->gambar_buku) }}" class="h-32 rounded-xl shadow-md border border-slate-100 mb-4">
                                @endif
                                <input type="file" name="gambar_buku" class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-600">
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sinopsis</label>
                            <textarea name="sinopsis" rows="8" class="w-full bg-slate-50 border border-slate-100 rounded-[1.5rem] py-4 px-5 text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all resize-none custom-scrollbar">{{ $buku->sinopsis }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-4 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Penerbit</label>
                                <input type="text" name="penerbit" value="{{ $buku->penerbit }}" required class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Kota</label>
                                <input type="text" name="tempat_terbit" value="{{ $buku->tempat_terbit }}" class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Tahun</label>
                                <input type="number" name="tahun_terbit" value="{{ $buku->tahun_terbit }}" required class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Halaman</label>
                                <input type="number" name="jumlah_halaman" value="{{ $buku->jumlah_halaman }}" class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="px-16 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>