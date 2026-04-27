<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Usulan Buku - ITH Lib</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8FAFC] p-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase italic">Riwayat <span class="text-blue-600">Usulan Buku</span></h1>
                <p class="text-slate-500 text-sm italic">Daftar buku yang Anda usulkan untuk pengadaan perpustakaan.</p>
            </div>
            <a href="{{ route('beranda') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest flex items-center gap-2 transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        {{-- Tabel Riwayat Usulan --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Detail Buku Usulan</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Tgl Usul</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Status</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Catatan Pustakawan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatUsulan as $u)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-journal-text text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-700 text-sm leading-tight">{{ $u->judul }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Penulis: {{ $u->penulis }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="text-xs font-bold text-slate-400">{{ $u->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="p-6 text-center">
                            @if($u->status == 'disetujui')
                                <span class="bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tight">
                                    <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                </span>
                            @elseif($u->status == 'ditolak')
                                <span class="bg-red-100 text-red-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tight">
                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tight">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                </span>
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            <p class="text-[11px] text-slate-500 italic">
                                {{ $u->catatan_admin ?? '-' }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-24 text-center">
                            <div class="opacity-20">
                                <i class="bi bi-send-x text-5xl mb-4 block"></i>
                            </div>
                            <p class="text-slate-400 italic text-sm font-medium uppercase tracking-widest">Belum ada usulan buku yang diajukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
