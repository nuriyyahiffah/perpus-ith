<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Katalog SIPUSTAKA - Perpustakaan ITH</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #E2E8F0 0%, #F8FAFC 100%);
            min-height: 100vh;
            color: #1E293B;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body class="antialiased">

    {{-- MEMANGGIL HEADER SERAGAM DARI FOLDER LAYOUTS --}}
    @include('layouts.header')

    {{-- HEADER JUDUL HALAMAN --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-4xl font-[900] text-[#2D3E50] italic uppercase tracking-tighter">
                    Katalog
                    <span class="text-indigo-800">Buku</span>
                </h1>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.3em] mt-2">
                    Eksplorasi referensi akademik digital
                </p>
            </div>
            <div class="h-2 w-16 bg-yellow-400 rounded-full hidden md:block"></div>
        </div>
    </div>

    {{-- MAIN KONTEN --}}
    <main class="max-w-7xl mx-auto px-6 pb-20">

        {{-- FORM FILTER & PENCARIAN --}}
        <form action="{{ route('katalog.index') }}"
            method="GET"
            class="space-y-6">

            <div class="flex flex-col md:flex-row gap-3">

                {{-- SEARCH --}}
                <div class="flex-1 relative group">
                    <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul, penulis, atau tahun..."
                        class="w-full pl-12 pr-6 py-4 bg-white/80 backdrop-blur border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 outline-none focus:border-indigo-800 focus:ring-4 focus:ring-indigo-100 transition-all shadow-sm">
                </div>

                {{-- FILTER PRODI --}}
                <div class="relative min-w-[200px]">
                    <select
                        name="prodi"
                        onchange="this.form.submit()"
                        class="w-full px-6 py-4 bg-white/80 backdrop-blur border border-slate-200 rounded-2xl text-[11px] font-black uppercase tracking-wider text-slate-600 outline-none cursor-pointer shadow-sm appearance-none pr-10">
                        <option value="">Semua Prodi</option>
                        @foreach($daftarProdi as $p)
                            <option value="{{ $p }}" {{ $prodiDipilih == $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                </div>

                {{-- SORT --}}
                <div class="relative min-w-[140px]">
                    <select
                        name="sort"
                        onchange="this.form.submit()"
                        class="w-full px-6 py-4 bg-white/80 backdrop-blur border border-slate-200 rounded-2xl text-[11px] font-black uppercase tracking-wider text-slate-600 outline-none cursor-pointer shadow-sm appearance-none pr-10">
                        <option value="">Urutan</option>
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="lama" {{ request('sort') == 'lama' ? 'selected' : '' }}>Terlama</option>
                        <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Judul A - Z</option>
                        <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Judul Z - A</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="px-10 py-4 bg-[#2D3E50] text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-indigo-900 transition-all active:scale-95 shadow-lg">
                    Cari
                </button>

            </div>

            {{-- INFO HASIL PENCARIAN --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    @if(request('search') || $prodiDipilih)
                        <p class="text-sm text-slate-500">
                            Menampilkan hasil untuk:
                            @if(request('search'))
                                <span class="font-bold text-indigo-700">"{{ request('search') }}"</span>
                            @endif
                            @if($prodiDipilih)
                                @if(request('search')) dan @endif
                                rekomendasi prodi <span class="font-bold text-emerald-600">"{{ $prodiDipilih }}"</span>
                            @endif
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <p class="text-xs text-slate-400 font-bold">
                        Total {{ $buku->total() }} buku ditemukan
                    </p>

                    @if(request('search') || request('sort') || $prodiDipilih)
                        <a href="{{ route('katalog.index') }}"
                            class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:underline">
                            <i class="bi bi-x-circle mr-1"></i>
                            Reset Filter
                        </a>
                    @endif
                </div>
            </div>

        </form>

        {{-- GRID BUKU --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-12">

            @forelse($buku as $item)
                <div class="bg-white/80 backdrop-blur p-4 rounded-[2rem] border border-white shadow-sm card-hover transition-all duration-300 group">

                    {{-- COVER BUKU --}}
                    <div class="aspect-[3/4] bg-slate-100 rounded-2xl mb-4 overflow-hidden flex items-center justify-center relative border border-slate-100">
                        @if($item->cover)
                            <img src="{{ asset('storage/'.$item->cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @elseif($item->gambar_buku)
                            <img src="{{ asset('images/' . $item->gambar_buku) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="text-center">
                                <i class="bi bi-book-half text-4xl text-slate-200"></i>
                                <p class="text-[8px] font-bold text-slate-300 uppercase mt-2">No Cover</p>
                            </div>
                        @endif

                        {{-- TAHUN --}}
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-lg shadow-sm border border-slate-100">
                            <span class="text-[9px] font-black text-indigo-800 italic leading-none">
                                {{ $item->tahun_terbit ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    {{-- CONTENT TEXT --}}
                    <div class="px-1">
                        <h3 class="text-xs font-black text-slate-800 uppercase leading-snug line-clamp-2 italic mb-1 min-h-[2.5rem] group-hover:text-indigo-800 transition">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter truncate mb-5">
                            <span class="text-indigo-600 mr-1 italic font-medium">By</span>
                            {{ $item->penulis }}
                        </p>

                        {{-- BUTTON --}}
                        <a href="{{ route('buku.detail', $item->id) }}"
                            class="w-full py-3.5 bg-slate-100 text-slate-600 group-hover:bg-[#2D3E50] group-hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all block text-center active:scale-95 shadow-sm">
                            Detail Buku
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white/50 backdrop-blur rounded-[3rem] border-2 border-dashed border-slate-200">
                    <i class="bi bi-search text-5xl text-slate-200 mb-4 block"></i>
                    <p class="text-slate-400 font-black italic uppercase tracking-[0.3em] text-[10px]">
                        Oopss! Koleksi tidak ditemukan
                    </p>
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="mt-20 flex justify-center">
            @if($buku instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $buku->appends(request()->query())->links() }}
            @endif
        </div>

        {{-- GUEST SECTION --}}
        @guest
            <div class="mt-24 p-12 bg-[#2D3E50] rounded-[3.5rem] text-center text-white relative overflow-hidden shadow-2xl">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-4">
                        Ingin Akses Penuh?
                    </h2>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mb-8 max-w-lg mx-auto leading-relaxed">
                        Masuk sebagai civitas akademika untuk melihat status ketersediaan buku secara real-time dan melakukan peminjaman digital.
                    </p>
                    <a href="{{ route('login') }}"
                        class="inline-block bg-[#10B981] text-white px-12 py-5 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-white hover:text-[#2D3E50] transition-all active:scale-95 shadow-xl shadow-emerald-500/20">
                        Masuk ke Akun Saya
                    </a>
                </div>
                <i class="bi bi-journals absolute -bottom-10 -right-10 text-[15rem] text-white/5 pointer-events-none transform rotate-12"></i>
            </div>
        @endguest

    </main>

    {{-- FOOTER --}}
    <footer class="py-16 text-center border-t border-slate-200/50">
        <div class="flex items-center justify-center gap-4 mb-6 opacity-30 grayscale">
            <img src="{{ asset('images/logo_ith.png') }}" class="h-8">
            <div class="h-4 w-px bg-slate-400"></div>
            <span class="font-black italic text-sm text-slate-600 uppercase tracking-tighter">
                Perpustakaan ITH
            </span>
        </div>
        <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.5em] italic">
            &copy; 2026 SIPUSTAKA • INSTITUT TEKNOLOGI BJ HABIBIE
        </p>
    </footer>

</body>
</html>
