<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Reservasi - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="antialiased text-slate-800">

    {{-- Navbar Sesuai Referensi Gambar --}}
    <nav class="bg-[#2D3E50] text-white py-4 px-6 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">

            {{-- Sisi Kiri: Tombol Kembali Pintar, Logo, dan Nama Instansi --}}
            <div class="flex items-center space-x-5">
                {{-- Navigasi Kembali Berbasis Role --}}
                @if(Auth::user()->role == 'mahasiswa')
                    <a href="{{ route('mahasiswa.beranda') }}" class="text-white hover:text-slate-300 transition text-xl flex items-center">
                        <i class="bi bi-arrow-left text-2xl font-bold"></i>
                    </a>
                @elseif(Auth::user()->role == 'dosen' || Auth::user()->role == 'kaprodi')
                    <a href="{{ route('dosen.beranda') }}" class="text-white hover:text-slate-300 transition text-xl flex items-center">
                        <i class="bi bi-arrow-left text-2xl font-bold"></i>
                    </a>
                @else
                    <a href="javascript:history.back()" class="text-white hover:text-slate-300 transition text-xl flex items-center">
                        <i class="bi bi-arrow-left text-2xl font-bold"></i>
                    </a>
                @endif

                {{-- Garis Pembatas Vertikal antara Tombol & Logo --}}
                <div class="h-8 w-[1px] bg-slate-500/40"></div>

                {{-- Logo dan Teks Instansi --}}
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-9">

                    {{-- Garis Pembatas Vertikal setelah Logo --}}
                    <div class="h-8 w-[1px] bg-slate-500/40 mx-1"></div>

                    <div class="flex flex-col">
                        <span class="text-xs font-black uppercase tracking-wider leading-none">PERPUSTAKAAN</span>
                        <span class="text-[8px] text-yellow-400 font-bold uppercase tracking-wider mt-1">Institut Teknologi Bacharuddin Jusuf Habibie</span>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Pengguna Aktif --}}
            <div class="flex flex-col text-right">
                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Pengguna Aktif</span>
                <span class="text-xs font-bold text-white tracking-wide leading-none">{{ Auth::user()->name }}</span>
            </div>

        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-6xl mx-auto">

            {{-- Header Halaman --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <span class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border border-indigo-100">
                        Layanan Antrean & Reservasi
                    </span>
                    <h1 class="text-3xl font-black text-[#2D3E50] uppercase tracking-tight mt-4">
                        {{-- Hanya Admin & Pustakawan yang melihat judul Manajemen Antrean --}}
                        {{ in_array(Auth::user()->role, ['admin', 'pustakawan']) ? 'Manajemen Antrean' : 'Reservasi Saya' }}
                    </h1>
                </div>
                <div class="bg-white px-8 py-4 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-50 hidden md:block">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Reservasi</p>
                    <p class="text-3xl font-black text-indigo-600 leading-none">{{ $reservasi->count() }}</p>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-50 transition-all duration-500 hover:shadow-indigo-100/50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                {{-- Kolom Mahasiswa hanya terlihat oleh Petugas Perpustakaan --}}
                                @if(in_array(Auth::user()->role, ['admin', 'pustakawan']))
                                    <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Mahasiswa</th>
                                @endif
                                <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Buku yang Dipesan</th>
                                <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Waktu Antre</th>
                                <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                                {{-- Kolom Opsi Eksklusif untuk Admin & Pustakawan --}}
                                @if(in_array(Auth::user()->role, ['admin', 'pustakawan']))
                                    <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Opsi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($reservasi as $item)
                            <tr class="hover:bg-slate-50/50 transition duration-300">
                                {{-- Identitas Pengantre hanya untuk Admin/Pustakawan --}}
                                @if(in_array(Auth::user()->role, ['admin', 'pustakawan']))
                                    <td class="p-8">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-100 uppercase overflow-hidden">
                                                {{ substr($item->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm text-[#2D3E50]">{{ $item->user->name }}</p>
                                                <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">{{ $item->user->nomor_identitas }}</p>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                <td class="p-8">
                                    <div class="flex flex-col">
                                        <p class="font-extrabold text-[#2D3E50] uppercase text-sm leading-tight mb-1 group-hover:text-indigo-600 transition">{{ $item->buku->judul }}</p>
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-person text-slate-400 text-xs"></i>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $item->buku->penulis }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-8">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-600">{{ $item->created_at->format('d M Y') }}</span>
                                        <span class="text-[10px] text-indigo-400 font-black uppercase tracking-tighter">{{ $item->created_at->format('H:i') }} WITA</span>
                                    </div>
                                </td>

                                <td class="p-8 text-center">
                                    <span class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-wider border shadow-sm inline-flex items-center gap-2
                                        {{ $item->status == 'menunggu'
                                            ? 'bg-amber-50 text-amber-600 border-amber-100 shadow-amber-50'
                                            : 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-emerald-50' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $item->status == 'menunggu' ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                                        {{ $item->status }}
                                    </span>
                                </td>

                                {{-- Aksi Konfirmasi: Dikunci Kuat Hanya untuk Admin & Pustakawan --}}
                                @if(in_array(Auth::user()->role, ['admin', 'pustakawan']))
                                    <td class="p-8 text-center">
                                        @if($item->status == 'menunggu')
                                            <form action="{{ route('reservasi.konfirmasi', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Konfirmasi buku tersedia secara manual?')"
                                                        class="bg-[#2D3E50] hover:bg-emerald-600 text-white text-[9px] font-black uppercase px-5 py-3 rounded-2xl transition-all duration-300 shadow-xl shadow-slate-200 flex items-center gap-2 mx-auto group">
                                                    <i class="bi bi-megaphone-fill group-hover:rotate-12 transition"></i>
                                                    Konfirmasi
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex flex-col items-center">
                                                <div class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-1">
                                                    <i class="bi bi-check2-all text-lg"></i>
                                                </div>
                                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-[0.2em]">Selesai</span>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                {{-- Menyesuaikan colspan dinamis agar pembatas tabel tidak rusak --}}
                                <td colspan="{{ in_array(Auth::user()->role, ['admin', 'pustakawan']) ? '5' : '3' }}" class="p-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-100 shadow-inner">
                                            <i class="bi bi-inboxes text-4xl text-slate-200"></i>
                                        </div>
                                        <p class="text-slate-400 font-black uppercase text-xs tracking-[0.4em]">Tidak Ada Antrean Aktif</p>
                                        <p class="text-slate-300 text-[10px] mt-2 font-medium">Semua data reservasi akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-12 flex flex-col md:flex-row justify-between items-center border-t border-slate-100 pt-8 gap-4">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">
                    SIPUSTAKA ITH &copy; 2026 • Parepare, Indonesia
                </p>
                <div class="flex gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span class="text-[9px] font-black uppercase text-slate-400">Menunggu</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[9px] font-black uppercase text-slate-400">Tersedia</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
