<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 antialiased text-slate-900">

<div class="flex min-h-screen">
    {{-- Sidebar (Include atau Paste Sidebar Kamu di Sini) --}}
@if(Auth::user()->role == 'admin')
    @include('layouts.partials.sidebar-admin')
@else
    @include('layouts.partials.sidebar-pustakawan')
@endif

    <main class="flex-1 p-8">
        {{-- Header Section --}}
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Buku Tamu Pengunjung</h1>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">
                    <i class="bi bi-people-fill me-1"></i> Daftar Kunjungan Perpustakaan ITH Hari Ini
                </p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="text-right">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter block mb-1">Total Pengunjung</span>
                        <span class="text-xl font-black text-blue-600 leading-none">{{ $pengunjung->count() }}</span>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="bi bi-person-check-fill text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em]">Waktu</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em]">Nama Pengunjung</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em]">Status Pengunjung</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em]">Keperluan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pengunjung as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="text-sm font-black text-slate-800 tracking-tight">
                                {{ $p->created_at->format('H:i') }}
                            </div>
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">WITA</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 group-hover:bg-blue-500 group-hover:text-white transition-colors rounded-xl flex items-center justify-center font-black text-[10px] text-slate-400">
                                    {{ strtoupper(substr($p->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-800 tracking-tight capitalize">{{ $p->nama }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $p->identitas }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $status = strtolower($p->status_pengunjung);
                                $badgeClass = match($status) {
                                    'mahasiswa' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'dosen' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'tendik' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    default => 'bg-slate-50 text-slate-500 border-slate-100',
                                };
                                $icon = match($status) {
                                    'mahasiswa' => 'bi-mortarboard-fill',
                                    'dosen' => 'bi-person-badge-fill',
                                    'tendik' => 'bi-briefcase-fill',
                                    default => 'bi-person-fill',
                                };
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $badgeClass }} inline-flex items-center gap-2">
                                <i class="bi {{ $icon }}"></i>
                                {{ $p->status_pengunjung }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="max-w-[200px]">
                                <span class="text-[10px] text-slate-500 font-medium italic leading-relaxed">
                                    "{{ $p->keperluan }}"
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-person-vcard text-3xl text-slate-200"></i>
                            </div>
                            <h3 class="text-slate-400 font-black uppercase text-[11px] tracking-[0.3em]">Belum Ada Pengunjung Hari Ini</h3>
                            <p class="text-[9px] text-slate-300 uppercase mt-2 font-bold italic tracking-widest">Data akan muncul secara real-time saat pengunjung mengisi buku tamu</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>