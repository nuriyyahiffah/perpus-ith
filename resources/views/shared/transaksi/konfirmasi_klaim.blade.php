<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Klaim - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 antialiased text-slate-900" x-data="{ tab: 'konfirmasi' }">

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @if(Auth::user()->role == 'admin')
        @include('layouts.partials.sidebar-admin')
    @else
        @include('layouts.partials.sidebar-pustakawan')
    @endif

    <main class="flex-1 p-8">
        {{-- Header Section --}}
        <div class="mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Konfirmasi Klaim Buku</h1>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">
                    <i class="bi bi-info-circle me-1"></i> Verifikasi pengambilan buku oleh Dosen/Kaprodi ITH
                </p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="text-right border-r border-slate-100 pe-4">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter block mb-1">Antrean</span>
                    <span class="text-xl font-black text-blue-600 leading-none">{{ $klaimDosen->where('status', 'pending')->count() }}</span>
                </div>
                <a href="{{ url()->previous() }}" class="text-slate-300 hover:text-slate-600 transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </a>
            </div>
        </div>

        {{-- Tabs Navigasi --}}
        <div class="flex gap-4 mb-6">
            <button 
                @click="tab = 'konfirmasi'"
                :class="tab === 'konfirmasi' ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'bg-white text-slate-400 border-slate-100'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all">
                Konfirmasi Klaim
            </button>
            <button 
                @click="tab = 'riwayat'"
                :class="tab === 'riwayat' ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'bg-white text-slate-400 border-slate-100'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all">
                Riwayat Klaim
            </button>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-[10px] font-black uppercase tracking-widest">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Table Container --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em]">Pengaju (Dosen)</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em]">Detail Buku</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-center">
                            <span x-text="tab === 'konfirmasi' ? 'Aksi' : 'Status Akhir'"></span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    
                    {{-- TAB 1: KONFIRMASI (PENDING) --}}
                    <template x-if="tab === 'konfirmasi'">
                        @forelse($klaimDosen->where('status', 'pending') as $klaim)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-7">
                                <div class="text-sm font-black text-slate-800 tracking-tight">{{ $klaim->user->name }}</div>
                                <div class="text-[10px] text-blue-500 font-black uppercase tracking-tighter mt-1 italic">{{ $klaim->prodi }}</div>
                            </td>
                            <td class="px-8 py-7">
                                <div class="text-xs font-bold text-slate-700 italic uppercase tracking-tighter">"{{ $klaim->buku->judul }}"</div>
                                <div class="text-[10px] text-slate-400 font-medium mt-1">
                                    <i class="bi bi-journal-bookmark-fill me-1"></i>MK: {{ $klaim->mata_kuliah }}
                                </div>
                            </td>
                            <td class="px-8 py-7">
                                <div class="flex justify-center gap-3">
                                    <form action="{{ route('shared.peminjaman.approve', $klaim->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center bg-emerald-500 text-white rounded-2xl hover:bg-emerald-600 shadow-lg shadow-emerald-100 transition-all group">
                                            <i class="bi bi-check-lg text-lg group-hover:scale-125 transition-transform"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('shared.peminjaman.reject', $klaim->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Tolak klaim ini?')" class="w-10 h-10 flex items-center justify-center bg-rose-500 text-white rounded-2xl hover:bg-rose-600 shadow-lg shadow-rose-100 transition-all group">
                                            <i class="bi bi-x-lg text-lg group-hover:scale-125 transition-transform"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-32 text-center text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">Antrean klaim kosong</td>
                        </tr>
                        @endforelse
                    </template>

                    {{-- TAB 2: RIWAYAT (DISETUJUI & DITOLAK) --}}
                    <template x-if="tab === 'riwayat'">
                        @forelse($klaimDosen->whereIn('status', ['disetujui', 'ditolak']) as $klaim)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-7">
                                <div class="text-sm font-black text-slate-800 tracking-tight">{{ $klaim->user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-black uppercase mt-1 tracking-tighter">
                                    {{ $klaim->updated_at->format('d M Y | H:i') }}
                                </div>
                            </td>
                            <td class="px-8 py-7">
                                <div class="text-xs font-bold text-slate-700 italic uppercase">"{{ $klaim->buku->judul }}"</div>
                                <div class="text-[10px] text-slate-400 font-medium tracking-tighter italic">MK: {{ $klaim->mata_kuliah }}</div>
                            </td>
                            <td class="px-8 py-7 text-center">
                                @if($klaim->status == 'disetujui')
                                    <span class="bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-200 inline-block shadow-sm">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                @else
                                    <span class="bg-rose-100 text-rose-600 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-rose-200 inline-block shadow-sm">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-32 text-center text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">Belum ada riwayat klaim</td>
                        </tr>
                        @endforelse
                    </template>

                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>