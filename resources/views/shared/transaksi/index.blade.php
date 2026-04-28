<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sirkulasi Peminjaman - SIPUSTAKA</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased" x-data="{ modalKembali: null }">

    <div class="flex min-h-screen">
        {{-- Sidebar Dinamis --}}
        @if(Auth::user()->role == 'admin')
            @include('layouts.partials.sidebar-admin')
        @else
            @include('layouts.partials.sidebar-pustakawan')
        @endif

        <main class="flex-1 p-6 lg:p-10 overflow-y-auto">

            {{-- Notifikasi --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="bi bi-check-circle-fill text-xl"></i>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase tracking-widest leading-none">Berhasil</span>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                        Sirkulasi <span class="text-blue-600">Peminjaman</span>
                    </h1>
                    <p class="text-slate-500 text-[10px] mt-2 font-bold uppercase tracking-widest italic">
                        Manajemen Peminjaman & Pengembalian Buku Perpustakaan ITH
                    </p>
                </div>

                {{-- PERBAIKAN: Status Filter disesuaikan dengan Controller --}}
                <div class="flex bg-white p-1 rounded-xl border border-slate-200 shadow-sm">
                    <a href="{{ route('shared.transaksi.index') }}"
                       class="px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all {{ !request('status') ? 'bg-slate-900 text-white' : 'text-slate-400 hover:text-slate-600' }}">
                        Semua
                    </a>
                    <a href="{{ route('shared.transaksi.index', ['status' => 'dipinjam']) }}"
                       class="px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all {{ request('status') == 'dipinjam' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-600' }}">
                        Dipinjam
                    </a>
                    <a href="{{ route('shared.transaksi.index', ['status' => 'dikembalikan']) }}"
                       class="px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all {{ request('status') == 'dikembalikan' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-slate-600' }}">
                        Kembali
                    </a>
                </div>
            </header>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">Peminjam</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">Buku & Eksemplar</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Tgl Pinjam</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Deadline</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status / Kondisi</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($transaksi as $t)
                            @php
                                // PERBAIKAN: Gunakan strtolower untuk keamanan pengecekan
                                $statusLower = strtolower($t->status);
                                $deadline = \Carbon\Carbon::parse($t->tgl_kembali);
                                $isTerlambat = ($statusLower == 'dipinjam') && \Carbon\Carbon::now()->gt($deadline);
                            @endphp

                            <tr class="hover:bg-slate-50/80 transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-slate-800 flex items-center justify-center text-white font-bold text-xs shadow-md">
                                            {{ substr($t->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 uppercase text-xs tracking-tight">{{ $t->user->name ?? 'N/A' }}</p>
                                            <p class="text-[9px] text-blue-500 font-bold italic tracking-wider">{{ $t->user->nomor_identitas ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <p class="font-bold text-slate-700 uppercase leading-tight text-xs mb-1">{{ $t->buku->judul ?? 'N/A' }}</p>
                                        <span class="text-[8px] font-black bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100 italic uppercase tracking-widest w-fit">
                                            NO. INDUK: {{ $t->no_induk }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-center text-[10px] font-bold text-slate-600">
                                    {{ \Carbon\Carbon::parse($t->tgl_pinjam)->format('d M Y') }}
                                </td>

                                <td class="px-8 py-6 text-center text-[10px] font-bold {{ $isTerlambat ? 'text-red-500' : 'text-slate-500' }}">
                                    {{ $deadline->format('d/m/Y') }}
                                </td>

                                <td class="px-8 py-6 text-center">
                                    @if($statusLower == 'dipinjam')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-black uppercase text-[8px] border border-blue-200 shadow-sm">
                                            SEDANG DIPINJAM
                                        </span>
                                    @else
                                        <div class="flex flex-col items-center">
                                            @php
                                                // PERBAIKAN: Gunakan strtolower pada kondisi kembali
                                                $kondisi = strtolower($t->kondisi_kembali);
                                                $color = match($kondisi) {
                                                    'rusak' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                    'hilang' => 'bg-red-100 text-red-700 border-red-200',
                                                    default => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 {{ $color }} rounded-full font-black uppercase text-[8px] border">
                                                {{ strtoupper($t->kondisi_kembali ?? 'dikembalikan') }}
                                            </span>
                                            @if($t->denda_fisik > 0)
                                                <p class="text-[9px] text-red-600 font-black mt-1 uppercase">Denda: Rp {{ number_format($t->denda_fisik, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        @if($statusLower == 'dipinjam')
                                            <button @click="modalKembali = {{ $t->id }}"
                                                class="w-full max-w-[130px] bg-slate-900 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-all duration-300 shadow-md flex items-center justify-center gap-2 group">
                                                <i class="bi bi-arrow-return-left group-hover:-translate-x-1 transition-transform"></i>
                                                Kembalikan
                                            </button>

                                            @if(!$t->is_extended && !$isTerlambat)
                                                <form action="{{ route('shared.peminjaman.extend', $t->id) }}" method="POST" class="w-full max-w-[130px]">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" onclick="return confirm('Perpanjang masa pinjam 7 hari?')"
                                                        class="w-full bg-white border-2 border-blue-100 hover:border-blue-500 hover:text-blue-600 text-blue-500 px-4 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2">
                                                        <i class="bi bi-clock-history"></i> Perpanjang
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-[8px] font-black text-slate-300 uppercase italic">Selesai</span>
                                        @endif
                                    </div>

                                    {{-- MODAL PENGEMBALIAN --}}
                                    <div x-show="modalKembali === {{ $t->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
                                        <div @click.away="modalKembali = null" class="bg-white rounded-[2rem] p-8 shadow-2xl w-full max-w-sm border border-slate-100 animate-in zoom-in duration-300 text-left">
                                            <div class="text-center mb-6">
                                                <div class="w-16 h-16 bg-slate-50 text-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                                                    <i class="bi bi-journal-check"></i>
                                                </div>
                                                <h3 class="text-lg font-[900] text-slate-800 uppercase italic tracking-tighter">Cek Kondisi Buku</h3>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Silahkan pilih kondisi fisik saat ini</p>
                                            </div>

                                            <form action="{{ route('shared.transaksi.kembalikan', $t->id) }}" method="POST">
                                                @csrf
                                                <div class="space-y-4">
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Kembali</label>
                                                        <select name="kondisi_kembali" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-slate-700 outline-none text-sm appearance-none">
                                                            {{-- PERBAIKAN: Gunakan value huruf kecil agar sinkron dengan ENUM --}}
                                                            <option value="baik">✅ Kembali Baik</option>
                                                            <option value="rusak">⚠️ Kondisi Rusak</option>
                                                            <option value="hilang">❌ Buku Hilang</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Denda Kerusakan (Rp)</label>
                                                        <input type="number" name="denda_fisik" placeholder="0" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-slate-700 outline-none text-sm">
                                                    </div>

                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Catatan</label>
                                                        <textarea name="catatan_kondisi" rows="2" placeholder="Halaman sobek..." class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-slate-700 outline-none text-sm"></textarea>
                                                    </div>
                                                </div>

                                                <div class="flex gap-3 mt-8">
                                                    <button type="button" @click="modalKembali = null" class="flex-1 px-4 py-3 rounded-xl text-[10px] font-black uppercase text-slate-400">Batal</button>
                                                    <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-200">Konfirmasi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-32 text-center">
                                    <div class="flex flex-col items-center opacity-20 text-slate-400">
                                        <i class="bi bi-inboxes text-6xl mb-4"></i>
                                        <p class="font-black uppercase tracking-[0.3em] text-xs italic">Belum Ada Transaksi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <footer class="mt-12 text-center pb-8">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.3em]">
                    &copy; 2026 • PERPUSTAKAAN INSTITUT TEKNOLOGI BACHARUDDIN JUSUF HABIBIE
                </p>
            </footer>
        </main>
    </div>

</body>
</html>
