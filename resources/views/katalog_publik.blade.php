<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Pustaka - Digital Library ITH</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased" x-data="{ search: '', selectedCategory: 'Semua', selectedBook: null }">

    @include('layouts.header')

    <main class="container mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-[900] text-slate-800 tracking-tight uppercase italic">
                Jelajahi <span class="text-blue-600">Koleksi Buku</span>
            </h1>
            <div class="h-1 w-16 bg-yellow-400 mx-auto mt-3 rounded-full"></div>
            <p class="text-slate-500 text-xs font-medium mt-3">Temukan buku referensi kuliah dan riset teknologi terbaik di Kampus ITH Parepare.</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-10 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="w-full md:w-1/3 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="bi bi-search text-xs"></i>
                </span>
                <input type="text" x-model="search"
                    class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    placeholder="Cari judul buku atau penulis...">
            </div>

            <div class="flex flex-wrap gap-2 w-full md:w-auto justify-end text-[10px] font-black uppercase tracking-wider">
                <template x-for="cat in ['Semua', 'Sains', 'Teknologi', 'Komputer', 'Umum']">
                    <button @click="selectedCategory = cat"
                        :class="selectedCategory === cat ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                        class="px-4 py-2.5 rounded-xl transition duration-200" x-text="cat">
                    </button>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($semuaBuku as $buku)
                @php
                    $bookDetail = json_encode([
                        'judul' => $buku->judul,
                        'penulis' => $buku->penulis,
                        'penerbit' => $buku->penerbit ?? '-',
                        'tahun' => $buku->tahun_terbit ?? '-',
                        'kategori' => $buku->kategori ?? 'Umum',
                        'isbn' => $buku->isbn ?? '-',
                        'stok' => $buku->stok_tersedia ?? 0,
                        'deskripsi' => $buku->deskripsi ?? 'Tidak ada ringkasan sinopsis untuk buku ini.',
                        'cover' => $buku->cover ? asset('storage/' . $buku->cover) : 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=400'
                    ]);
                @endphp

                <div @click='selectedBook = {!! $bookDetail !!}'
                     x-show="(selectedCategory === 'Semua' || '{{ $buku->kategori ?? 'Umum' }}' === selectedCategory) && (search === '' || '{{ strtolower($buku->judul) }}'.includes(search.toLowerCase()) || '{{ strtolower($buku->penulis) }}'.includes(search.toLowerCase()))"
                     class="cursor-pointer transition transform hover:scale-105 duration-300">
                    <x-card-buku :buku="$buku" />
                </div>
            @endforeach
        </div>
    </main>

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
         x-show="selectedBook !== null" x-cloak x-transition>
        <div class="bg-white rounded-[2.5rem] w-full max-w-2xl p-6 md:p-8 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto" @click.away="selectedBook = null">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase" x-text="selectedBook?.kategori"></span>
                    <h3 class="text-xl font-black text-slate-800 mt-2" x-text="selectedBook?.judul"></h3>
                </div>
                <button @click="selectedBook = null" class="text-slate-400 hover:text-slate-600 text-xl"><i class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <img :src="selectedBook?.cover" class="w-full aspect-[3/4] object-cover rounded-2xl shadow-md border">
                <div class="md:col-span-2 flex flex-col justify-between">
                    <div class="space-y-3 text-xs">
                        <p class="text-slate-500" x-text="selectedBook?.deskripsi"></p>
                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-4 rounded-xl font-bold text-slate-700">
                            <div><span class="text-[9px] uppercase text-slate-400 block">Penulis</span> <span x-text="selectedBook?.penulis"></span></div>
                            <div><span class="text-[9px] uppercase text-slate-400 block">Penerbit</span> <span x-text="selectedBook?.penerbit"></span></div>
                        </div>
                    </div>
                    <div class="mt-4 p-4 rounded-xl bg-amber-50 text-amber-800 text-center font-bold text-xs">
                        Silakan <a href="{{ route('login') }}" class="text-blue-600 underline">Login Terlebih Dahulu</a> Untuk Dapat Melakukan Peminjaman Buku ini.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-[#1E293B] text-white py-12 mt-20">
        <div class="container mx-auto px-8 text-center">
            <img src="{{ asset('images/logo_ith.png') }}" class="h-10 mx-auto mb-6 opacity-40 grayscale hover:grayscale-0 transition duration-500">
            <div class="h-px w-16 bg-white/10 mx-auto mb-6"></div>
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.5em]">&copy; 2026 SIPUSTAKA ITH • Kampus Parepare</p>
        </div>
    </footer>

</body>
</html>