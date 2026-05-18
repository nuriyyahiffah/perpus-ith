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
        [x-cloak] { display: none !important; }
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
                        Edit <span class="text-blue-600">Katalog & Eksemplar</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-2 font-semibold italic uppercase tracking-wider">Update Data: {{ $buku->judul }}</p>
                </div>
                
                <a href="{{ route('shared.buku.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 text-slate-400 px-6 py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all hover:text-red-500 active:scale-95 shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </header>

            {{-- Tampilan Alert Error Validasi Server --}}
            @if ($errors->any())
                <div class="mb-6 p-5 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-lg"></i>
                    <div>
                        <h4 class="text-xs font-black text-red-800 uppercase tracking-wider mb-1">Periksa Kembali Isian Anda:</h4>
                        <ul class="list-disc pl-4 text-xs text-red-600 font-semibold space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <form action="{{ route('shared.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')
                    
                    {{-- Judul & ISBN --}}
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Informasi Dasar</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <input type="text" name="judul" x-model="judul" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all" placeholder="Judul Buku">
                            </div>
                            <div>
                                <input type="text" name="isbn" value="{{ $buku->isbn }}" placeholder="ISBN" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- No Panggil Preview & Input Pengarang --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Pengarang Utama</label>
                                <select name="tipe_pengarang_utama" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-xs font-bold text-slate-700 outline-none">
                                    <option value="Orang Perseorangan" {{ $buku->tipe_pengarang_utama == 'Orang Perseorangan' ? 'selected' : '' }}>Orang Perseorangan</option>
                                    <option value="Badan Korporasi" {{ $buku->tipe_pengarang_utama == 'Badan Korporasi' ? 'selected' : '' }}>Badan Korporasi / Organisasi</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest ml-1 italic">Nama Penulis Utama</label>
                                <input type="text" name="penulis" x-model="penulis" required class="w-full bg-blue-50/30 border border-blue-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:bg-white transition-all">
                            </div>
                        </div>
                        <div class="p-6 bg-blue-600 rounded-[2rem] shadow-xl shadow-blue-500/20 transition-all md:mt-7">
                            <p class="text-[8px] font-black text-blue-100 uppercase mb-1 italic">Preview No. Panggil Baru:</p>
                            <p class="text-2xl font-[900] text-white italic tracking-tight" x-text="noPanggil"></p>
                            <input type="hidden" name="no_panggil" :value="noPanggil">
                        </div>
                    </div>

                    {{-- Klasifikasi, Cover & Sinopsis --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Klasifikasi DDC</label>
                                <input type="text" name="klasifikasi" x-model="klasifikasi" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all" placeholder="Contoh: 600">
                            </div>
                            
                            <div class="pt-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Cover Saat Ini:</p>
                                @if($buku->gambar_buku)
                                    <img src="{{ asset('images/'.$buku->gambar_buku) }}" class="h-32 rounded-xl shadow-md border border-slate-100 mb-4 object-cover">
                                @else
                                    <div class="h-32 w-24 bg-slate-100 border border-dashed border-slate-200 rounded-xl flex items-center justify-center mb-4">
                                        <i class="bi bi-image text-slate-300 text-xl"></i>
                                    </div>
                                @endif
                                <input type="file" name="gambar_buku" class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-600">
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sinopsis</label>
                            <textarea name="sinopsis" rows="7" class="w-full bg-slate-50 border border-slate-100 rounded-[1.5rem] py-4 px-5 text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all resize-none custom-scrollbar" placeholder="Tulis ringkasan atau sinopsis buku di sini...">{{ $buku->sinopsis }}</textarea>
                        </div>
                    </div>

                    {{-- Data Publikasi (Penerbit, Kota, Tahun, Halaman) --}}
                    <div class="space-y-4 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detail Publikasi & Fisik</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Penerbit</label>
                                <input type="text" name="penerbit" value="{{ $buku->penerbit }}" required class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold text-slate-700 outline-none">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Kota / Tempat Terbit</label>
                                <input type="text" name="tempat_terbit" value="{{ $buku->tempat_terbit }}" required class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold text-slate-700 outline-none">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" value="{{ $buku->tahun_terbit }}" required class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold text-slate-700 outline-none">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Jumlah Halaman</label>
                                <input type="number" name="jumlah_halaman" value="{{ $buku->jumlah_halaman }}" class="w-full bg-white border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold text-slate-700 outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- REVISI - MODUL 2: INTEGRASI UNIT STOK EKSEMPLAR (BISA KETIK MANUAL) --}}
                    <div class="space-y-6 bg-blue-50/20 p-6 rounded-[2rem] border border-blue-100/50"
                         x-data="{
                            stok: {{ $buku->eksemplars->count() }},
                            // Parsing koleksi lama dari database ke format Array Javascript
                            items: [
                                @foreach($buku->eksemplars as $eksemplar)
                                    { id: '{{ $eksemplar->id }}', no_induk: '{{ $eksemplar->no_induk }}', no_barcode: '{{ $eksemplar->no_barcode }}', status: 'LAMA' },
                                @endforeach
                            ],
                            syncFields() {
                                let target = parseInt(this.stok) || 0;
                                if (target < 0) { target = 0; this.stok = 0; }
                                
                                if (target > this.items.length) {
                                    // Jika angka dinaikkan, tambahkan baris input kosong baru
                                    let selisih = target - this.items.length;
                                    for (let i = 0; i < selisih; i++) {
                                        this.items.push({ id: '', no_induk: '', no_barcode: '', status: 'BARU' });
                                    }
                                } else if (target < this.items.length) {
                                    // Jika angka diturunkan, potong dari baris paling belakang
                                    this.items = this.items.slice(0, target);
                                }
                            }
                         }"
                         x-init="syncFields()">
                        
                        <div class="flex items-center gap-2 ml-1">
                            <i class="bi bi-boxes text-blue-500 text-sm"></i>
                            <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Modul Unit Fisik (Eksemplar Buku)</label>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4 border-b border-dashed border-slate-200">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Total Unit Terdaftar</label>
                                <div class="relative mt-1">
                                    <input type="number" 
                                           name="total_stok" 
                                           x-model="stok" 
                                           @input="syncFields()"
                                           required 
                                           class="w-full bg-white border border-slate-200 rounded-xl py-3.5 pl-5 pr-12 text-sm font-black text-blue-600 outline-none focus:ring-2 focus:ring-blue-500/20">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 uppercase">Unit</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Sumber Pengadaan (Untuk Unit Baru)</label>
                                <select name="jenis_sumber" class="w-full bg-white border border-slate-200 rounded-xl py-3.5 px-4 text-xs font-bold text-slate-700 outline-none mt-1">
                                    <option value="Pembelian" {{ isset($buku->eksemplars->first()->jenis_sumber) && $buku->eksemplars->first()->jenis_sumber == 'Pembelian' ? 'selected' : '' }}>Pembelian Instansi</option>
                                    <option value="Hibah / Sumbangan">Hibah / Sumbangan Mahasiswa</option>
                                    <option value="Droping">Droping Perpustakaan Pusat</option>
                                </select>
                            </div>
                        </div>

                        {{-- Daftar Form Pengisian Nomor Induk Koleksi Buku --}}
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1 block">Daftar Pengenal Kode Unik Eksemplar Fisik:</label>
                            
                            <div class="max-h-64 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center bg-white p-3 rounded-xl border border-slate-100 shadow-sm transition-all">
                                        <div class="md:col-span-1 flex items-center justify-center">
                                            <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500" x-text="index + 1"></span>
                                        </div>
                                        
                                        <div class="md:col-span-6">
                                            <div class="relative">
                                                <input type="text" 
                                                       name="no_induk[]" 
                                                       x-model="item.no_induk"
                                                       required
                                                       placeholder="Ketik No. Induk Fisik Buku..." 
                                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 pl-9 text-xs font-bold text-slate-700 outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/10">
                                                <i class="bi bi-tag-fill absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                            </div>
                                        </div>

                                        <div class="md:col-span-5">
                                            <div class="relative">
                                                <input type="text" 
                                                       name="no_barcode[]" 
                                                       x-model="item.no_barcode"
                                                       placeholder="Barcode / No. Inventaris (Opsional)" 
                                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 pl-9 text-xs font-semibold text-slate-600 outline-none focus:bg-white">
                                                <i class="bi bi-barcode absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <p class="text-[10px] text-slate-400 font-medium leading-relaxed italic mt-2">
                                <i class="bi bi-info-circle-fill text-blue-500 mr-0.5"></i> 
                                Jika Anda menambah total unit, baris baru akan muncul secara otomatis. Pastikan kode nomor induk yang Anda ketik tidak kembar dengan koleksi lain di sistem.
                            </p>
                        </div>
                    </div>

                    {{-- Parameter Tambahan Pengarang --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Peran Tambahan (Optional)</label>
                            <input type="text" name="peran_tambahan" value="{{ $buku->peran_tambahan }}" placeholder="Contoh: Editor / Penerjemah" class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold text-slate-700 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Pengarang Tambahan (Optional)</label>
                            <input type="text" name="pengarang_tambahan" value="{{ $buku->pengarang_tambahan }}" placeholder="Nama Pengarang Tambahan" class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3.5 px-4 text-xs font-bold text-slate-700 outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="px-16 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all tracking-wider">
                            Simpan Perubahan & Sinkron
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>