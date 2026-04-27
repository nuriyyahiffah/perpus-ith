<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog & Eksemplar - SIPUSTAKA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>

<body class="bg-[#F1F5F9] antialiased" 
    x-data="{ 
        openModalBuku: false, 
        openModalEksemplar: {{ session('open_eksemplar') ? 'true' : 'false' }},
        search: '',
        judul: '', penulis: '', klasifikasi: '',
        jumlahEksemplar: 1,
        get noPanggil() {
            if(!this.klasifikasi || !this.penulis || !this.judul) return '-';
            let pnl = this.penulis.trim().substring(0, 3).toUpperCase();
            let jdl = this.judul.trim().charAt(0).toLowerCase();
            return `${this.klasifikasi} ${pnl} ${jdl}`;
        },
        get rows() { return Array.from({length: this.jumlahEksemplar}, (_, i) => i) }
    }">

    <div class="flex min-h-screen">
        @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto text-slate-700 text-[11px]">
            {{-- Header --}}
            <header class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                        Katalog <span class="text-blue-600">& Eksemplar</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-2 font-semibold italic">Perpustakaan Institut Teknologi Bacharuddin Jusuf Habibie</p>
                </div>

                <button @click="openModalBuku = true" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-blue-500/20 active:scale-95">
                    <i class="bi bi-journal-plus"></i> Tambah Buku
                </button>
            </header>

            {{-- Alert System --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl text-xs font-bold shadow-lg shadow-emerald-200 flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500 text-white rounded-2xl text-xs font-bold shadow-lg shadow-red-200">
                    <div class="flex items-center gap-2 mb-2 uppercase font-black"><i class="bi bi-exclamation-triangle-fill"></i> Terjadi Kesalahan:</div>
                    <ul class="list-disc list-inside opacity-90 font-semibold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Table Collection --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <div class="relative w-full md:w-96">
                        <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" class="w-full bg-white border border-slate-200 rounded-2xl py-3.5 pl-12 pr-6 text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all" placeholder="Cari Koleksi...">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Detail Katalog</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">No. Induk (Master)</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">Kategori Prodi</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">Stok Fisik</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($buku as $b)
                            <tr class="hover:bg-slate-50/50 transition" x-show="'{{ addslashes(strtolower($b->judul . ' ' . $b->penulis)) }}'.includes(search.toLowerCase())">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        @if($b->gambar_buku)
                                            <img src="{{ asset('images/'.$b->gambar_buku) }}" class="w-10 h-14 object-cover rounded-lg shadow-sm">
                                        @else
                                            <div class="w-10 h-14 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300"><i class="bi bi-book"></i></div>
                                        @endif
                                        <div>
                                            <p class="font-black text-slate-800 uppercase leading-tight">{{ $b->judul }}</p>
                                            <p class="text-[10px] text-blue-600 font-bold italic uppercase mt-1">
                                                {{ $b->penulis }} | {{ $b->penerbit }} ({{ $b->tahun_terbit }})
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="font-mono text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                            {{ $b->no_panggil }}
                                        </span>
                                        @if($b->eksemplars && $b->eksemplars->count() > 0)
                                            <span class="text-[8px] text-slate-400 font-bold uppercase italic">
                                                SN: {{ $b->eksemplars->first()->no_induk }}
                                            </span>
                                        @else
                                            <span class="text-[8px] text-red-400 font-bold uppercase italic">SN: Belum Ada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border {{ $b->kategori_id ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                        <i class="bi {{ $b->kategori_id ? 'bi-tag-fill' : 'bi-exclamation-circle' }} mr-1"></i>
                                        {{ $b->kategori->nama_kategori ?? 'Belum Diklaim' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl font-[900] text-blue-600 leading-none tracking-tighter">
                                                {{ $b->eksemplars ? $b->eksemplars->where('status', 'tersedia')->count() : 0 }}
                                            </span>
                                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">Tersedia</span>
                                        </div>
                                        <div class="mt-2 px-3 py-1 bg-slate-100 rounded-full border border-slate-200">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">
                                                Total: {{ $b->eksemplars ? $b->eksemplars->count() : 0 }} Unit
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center gap-2">
                                        {{-- TOMBOL EDIT - PERBAIKAN DI SINI --}}
                                        <a href="{{ route('shared.buku.edit', $b->id) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        {{-- TOMBOL HAPUS --}}
                                        <form action="{{ route('shared.buku.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus seluruh katalog?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold italic uppercase">Belum ada koleksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL 1: TAMBAH BUKU --}}
    <div x-show="openModalBuku" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden max-h-[95vh] flex flex-col" @click.away="openModalBuku = false">
            
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h2 class="text-xl font-black text-slate-800 uppercase italic">Entri <span class="text-blue-600">Katalog Bibliografis</span></h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Lengkapi detail informasi buku di bawah ini</p>
                </div>
                <button @click="openModalBuku = false" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-red-500 transition-all shadow-sm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('shared.buku.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto custom-scrollbar p-8 space-y-8">
                @csrf
                
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul & Identitas</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="judul" x-model="judul" required placeholder="Judul Lengkap Buku" 
                            class="md:col-span-2 bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                        <input type="text" name="isbn" placeholder="ISBN (Contoh: 978-602-xxx)" 
                            class="bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div class="space-y-4 bg-blue-50/30 p-6 rounded-[2rem] border border-blue-100">
                        <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest ml-1 italic">Pengarang Utama</label>
                        <div class="flex gap-2">
                            <select name="tipe_pengarang_utama" class="bg-white border border-slate-200 rounded-xl py-3.5 px-3 text-[10px] font-bold text-slate-700 outline-none">
                                <option value="Nama Orang">Orang</option>
                                <option value="Nama Badan">Badan</option>
                            </select>
                            <input type="text" name="penulis" x-model="penulis" required placeholder="Nama Pengarang Utama" 
                                class="flex-1 bg-white border border-slate-200 rounded-xl py-3.5 px-5 text-xs font-bold text-slate-700 outline-none">
                        </div>
                    </div>
                    <div class="p-5 bg-blue-600 rounded-[2rem] shadow-xl shadow-blue-500/20">
                        <p class="text-[8px] font-black text-blue-100 uppercase mb-1 italic">Auto-Generate No. Panggil:</p>
                        <p class="text-2xl font-[900] text-white italic tracking-tight" x-text="noPanggil"></p>
                        <input type="hidden" name="no_panggil" :value="noPanggil">
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tambahan / Peran Lain</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select name="peran_tambahan" class="bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-5 text-xs font-bold text-slate-700 outline-none">
                            <option value="">-- Pilih Peran --</option>
                            <option value="Pengarang">Pengarang</option>
                            <option value="Editor">Editor</option>
                            <option value="Ilustrator">Ilustrator</option>
                            <option value="Penerjemah">Penerjemah</option>
                        </select>
                        <input type="text" name="pengarang_tambahan" placeholder="Nama Pengarang Tambahan (Opsional)" 
                            class="md:col-span-2 bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-5 text-xs font-bold text-slate-700 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Klasifikasi DDC</label>
                        <input type="text" name="klasifikasi" x-model="klasifikasi" required placeholder="Kode DDC (Contoh: 600)" 
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none">
                        
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block mt-4">Cover Buku</label>
                        <input type="file" name="gambar_buku" class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all">
                    </div>
                    
                    <div class="md:col-span-2 space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sinopsis Buku</label>
                        <textarea name="sinopsis" rows="5" placeholder="Tuliskan ringkasan isi buku secara lengkap di sini..." 
                            class="w-full bg-slate-50 border border-slate-100 rounded-[1.5rem] py-4 px-5 text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all resize-none custom-scrollbar"></textarea>
                    </div>
                </div>

                <div class="space-y-4 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detail Penerbitan & Fisik</label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <input type="text" name="penerbit" required placeholder="Penerbit" class="md:col-span-2 bg-white border border-slate-100 rounded-xl py-3.5 px-5 text-xs font-bold">
                        <input type="text" name="tempat_terbit" placeholder="Kota Terbit" class="bg-white border border-slate-100 rounded-xl py-3.5 px-5 text-xs font-bold">
                        <input type="number" name="tahun_terbit" required placeholder="Tahun" class="bg-white border border-slate-100 rounded-xl py-3.5 px-5 text-xs font-bold">
                        
                        <div class="flex gap-2 md:col-span-1">
                            <input type="number" name="jumlah_halaman" placeholder="hlm" class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold">
                            <select name="bahasa" class="bg-white border border-slate-100 rounded-xl py-3.5 px-2 text-[10px] font-bold outline-none">
                                <option value="Indonesia">ID</option>
                                <option value="Inggris">EN</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                    <button type="button" @click="openModalBuku = false" class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 hover:text-red-500 transition-all">Batal</button>
                    <button type="submit" class="px-12 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-blue-500/20 active:scale-95 transition-all">Simpan & Lanjut ke Eksemplar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 2: TAMBAH EKSEMPLAR --}}
    <div x-show="openModalEksemplar" x-transition x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="p-8 border-b border-emerald-100 flex justify-between items-center bg-emerald-600 text-white">
                <div>
                    <h2 class="text-xl font-black uppercase italic tracking-tight">Registrasi Eksemplar Fisik</h2>
                    <p class="text-[10px] font-bold uppercase tracking-widest mt-1 text-emerald-100">Masukkan Nomor Induk / Barcode</p>
                </div>
                <button @click="openModalEksemplar = false" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white/10 hover:bg-white/20 transition-all shadow-sm">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <form action="{{ route('shared.eksemplar.store') }}" method="POST" class="overflow-y-auto custom-scrollbar p-8 space-y-6">
                @csrf
                <input type="hidden" name="buku_id" value="{{ session('selected_buku_id') }}">

                @if(session('selected_buku_judul'))
                <div class="p-5 bg-emerald-50/50 border border-emerald-100 rounded-[1.5rem] flex items-center gap-4 border-l-4 border-l-emerald-500">
                    <div>
                        <p class="text-[9px] font-black text-emerald-600 uppercase italic leading-none">Sedang mendaftarkan fisik untuk:</p>
                        <p class="text-sm font-black text-slate-700 uppercase leading-none mt-1.5">{{ session('selected_buku_judul') }}</p>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50 p-6 rounded-[2rem] border border-slate-100 items-end">
                    <div class="space-y-1.5">
                        <p class="text-[9px] font-black text-slate-400 uppercase ml-1">Sumber</p>
                        <select name="jenis_sumber" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            <option value="Pembelian">Pembelian</option>
                            <option value="Hadiah/Hibah">Hadiah/Hibah</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-[9px] font-black text-slate-400 uppercase ml-1">Fisik</p>
                        <select name="bentuk_fisik" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            <option value="Buku">Buku</option>
                            <option value="Majalah">Majalah</option>
                            <option value="E-Book">E-Book</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-[9px] font-black text-slate-400 uppercase ml-1">Status</p>
                        <select name="status" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Dipinjam">Dipinjam</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-[9px] font-black text-emerald-600 uppercase ml-1 italic tracking-tighter">Tambah Baris</p>
                        <input type="number" x-model="jumlahEksemplar" min="1" max="50" class="w-full bg-emerald-600 text-white rounded-xl py-3 px-4 text-xs font-black border-none shadow-lg shadow-emerald-200 text-center outline-none">
                    </div>
                </div>

                <div class="space-y-3">
                    <template x-for="row in rows" :key="row">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-100 hover:border-emerald-200 transition-all">
                            <input type="text" name="no_induk[]" required placeholder="No. Induk (Wajib)" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold">
                            <input type="text" name="no_barcode[]" placeholder="No. Barcode" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold">
                            <input type="text" name="no_rfid[]" placeholder="No. RFID" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold">
                        </div>
                    </template>
                </div>

                <div class="flex justify-end items-center gap-6 pt-6 border-t border-slate-50">
                    <button type="button" @click="openModalEksemplar = false" class="text-[10px] font-black uppercase text-slate-400 hover:text-red-500 transition-all">Batal</button>
                    <button type="submit" class="px-10 py-4 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl hover:bg-emerald-700 transition-all">
                        Simpan Seluruh Koleksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>