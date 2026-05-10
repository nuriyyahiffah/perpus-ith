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
    </style>
</head>
<body class="antialiased text-slate-800">

    {{-- Navbar --}}
    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-8">
                <span class="text-[10px] font-bold uppercase tracking-wider">SIPUSTAKA <span class="text-yellow-400">Digital Library</span></span>
            </div>
            
            <a href="javascript:history.back()" class="text-[10px] font-bold uppercase hover:text-yellow-400 transition flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
            
            {{-- Header Halaman --}}
            <div class="flex justify-between items-center mb-10">
                <div>
                    <span class="px-5 py-2 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                        Layanan Antrean
                    </span>
                    <h1 class="text-3xl font-black text-[#2D3E50] uppercase tracking-tight mt-4">
                        {{ Auth::user()->role != 'mahasiswa' ? 'Daftar Semua Antrean' : 'Reservasi Saya' }}
                    </h1>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Data</p>
                    <p class="text-2xl font-black text-indigo-600">{{ $reservasi->count() }}</p>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'pustakawan')
                                    <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Mahasiswa</th>
                                @endif
                                <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Buku yang Dipesan</th>
                                <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Tanggal Input</th>
                                <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status Antrean</th>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'pustakawan')
                                    <th class="p-8 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($reservasi as $item)
                            <tr class="hover:bg-slate-50/30 transition duration-300">
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'pustakawan')
                                    <td class="p-8">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-bold text-xs uppercase">
                                                {{ substr($item->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm text-[#2D3E50]">{{ $item->user->name }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium tracking-tighter">{{ $item->user->nim ?? 'No NIM' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td class="p-8">
                                    <p class="font-bold text-[#2D3E50] uppercase text-sm leading-tight mb-1">{{ $item->buku->judul }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $item->buku->penulis }}</p>
                                </td>
                                <td class="p-8">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-600">{{ $item->created_at->format('d M Y') }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium italic">{{ $item->created_at->format('H:i') }} WITA</span>
                                    </div>
                                </td>
                                <td class="p-8 text-center">
                                    <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-wider border shadow-sm
                                        {{ $item->status == 'menunggu' 
                                            ? 'bg-amber-50 text-amber-600 border-amber-100 shadow-amber-50' 
                                            : 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-emerald-50' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'pustakawan')
                                    <td class="p-8 text-center">
                                        @if($item->status == 'menunggu')
                                            <form action="{{ route('reservasi.konfirmasi', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Konfirmasi bahwa buku ini sudah tersedia untuk diambil?')"
                                                        class="bg-[#2D3E50] hover:bg-indigo-600 text-white text-[9px] font-black uppercase px-4 py-2 rounded-xl transition-all duration-300 shadow-lg shadow-slate-200 flex items-center gap-2 mx-auto">
                                                    <i class="bi bi-check-circle-fill text-emerald-400"></i>
                                                    Konfirmasi
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[9px] font-bold text-slate-400 uppercase italic">
                                                <i class="bi bi-check-all text-emerald-500 text-base"></i> Tersedia
                                            </span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role != 'mahasiswa' ? '5' : '3' }}" class="p-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-calendar2-x text-5xl text-slate-200 mb-4"></i>
                                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.2em]">Data antrean tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Info --}}
            <p class="mt-8 text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">
                SIPUSTAKA ITH Digital Library System &copy; 2026
            </p>
        </div>
    </main>

</body>
</html>