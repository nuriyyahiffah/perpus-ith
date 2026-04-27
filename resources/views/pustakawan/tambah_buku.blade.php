<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Koleksi Buku - Pustakawan ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .hero-gradient { background: linear-gradient(180deg, #A7C5E0 0%, #F8FAFC 100%); }
    </style>
</head>

<body class="hero-gradient min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-2xl bg-white p-10 md:p-12 rounded-[3rem] shadow-xl border border-slate-100 relative overflow-hidden">

        <div class="mb-10 text-center relative">
            <div class="h-1.5 w-16 bg-yellow-500 mx-auto mb-6"></div>
            <h2 class="text-3xl font-black text-[#1A2B3C] uppercase tracking-tighter">Tambah Koleksi Buku</h2>
            <p class="text-slate-500 text-[11px] mt-2 tracking-[0.2em] uppercase font-bold">Manajemen Perpustakaan Fisik ITH</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 text-[11px] p-4 rounded-xl mb-6 italic">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('buku.simpan') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf



            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Judul Buku</label>
                    <input type="text" name="judul" placeholder="Contoh: Pemrograman Python"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm focus:border-blue-500 outline-none transition text-slate-700"
                        required value="{{ old('judul') }}">
                </div>


    <div class="space-y-2">
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Kode Buku</label>
        <input type="text" name="kode_buku" placeholder="Contoh: B-001"
            class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm focus:border-blue-500 outline-none transition text-slate-700 font-mono"
            required value="{{ old('kode_buku') }}">
    </div>

    <div class="space-y-2">
    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Kategori Buku</label>
    <select name="kategori_id"
        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm focus:border-blue-500 outline-none transition text-slate-700"
        required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategori as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
        @endforeach
    </select>
</div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Penulis</label>
                    <input type="text" name="penulis" placeholder="Nama lengkap penulis"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm focus:border-blue-500 outline-none transition text-slate-700"
                        required value="{{ old('penulis') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Penerbit</label>
                    <input type="text" name="penerbit" placeholder="ITH Press / Erlangga"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm focus:border-blue-500 outline-none transition text-slate-700"
                        required value="{{ old('penerbit') }}">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Stok Buku (Eks)</label>
                    <input type="number" name="stok" placeholder="0"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm focus:border-blue-500 outline-none transition text-slate-700"
                        required value="{{ old('stok') }}">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cover Buku (JPG/PNG)</label>
                <div class="relative group">
                    <input type="file" name="gambar_buku" accept="image/*"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-[#2D3E50] file:text-white file:font-bold hover:file:bg-blue-600 cursor-pointer bg-slate-50 p-2 rounded-2xl border border-slate-200"
                        required>
                </div>
                <p class="text-[9px] text-slate-400 italic ml-1">* Ukuran rekomendasi: 400x600 piksel</p>
            </div>

            <div class="pt-8 flex flex-col md:flex-row gap-4">
                <button type="submit"
                    class="flex-[2] bg-[#2D3E50] text-white font-black py-4 rounded-2xl hover:bg-blue-700 transition transform active:scale-95 shadow-lg shadow-blue-900/10 uppercase tracking-widest text-xs">
                    Simpan ke Katalog
                </button>
                <a href="{{ route('pustakawan.dashboard') }}"
                    class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition text-center text-[10px] flex items-center justify-center uppercase tracking-widest border border-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>

</body>
</html>
