<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Anggota - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased">

    <div class="flex min-h-screen">

        @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

        <div class="flex-1 flex flex-col min-w-0">
            <main class="p-6 lg:p-10">
                <header class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="{{ route('shared.anggota.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest mb-4 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm border border-blue-100">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                            Antrean <span class="text-blue-600">Aktivasi</span>
                        </h1>
                        <p class="text-slate-500 text-xs mt-3 font-semibold italic uppercase tracking-widest">Verifikasi Keanggotaan Baru</p>
                    </div>

                    <div class="bg-white px-6 py-4 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-xl">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 leading-none">Total Antrean</p>
                            <p class="text-xl font-black text-slate-800 leading-none mt-1">{{ count($calonAnggota) }} Member</p>
                        </div>
                    </div>
                </header>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-xs font-bold uppercase tracking-widest flex items-center gap-3">
                        <i class="bi bi-check-circle-fill text-lg"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                        <h2 class="font-black uppercase text-[11px] tracking-[0.2em] text-slate-400">Calon Anggota Terdaftar</h2>
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center w-20">No</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Nama & Identitas</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Kategori</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Prodi / Unit</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($calonAnggota as $index => $user)
                                <tr class="hover:bg-blue-50/30 transition duration-200 group">
                                    <td class="px-8 py-6 text-center font-black text-slate-300 group-hover:text-blue-500 text-xs">{{ $index + 1 }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="font-extrabold text-slate-700 text-sm uppercase tracking-tight group-hover:text-blue-600 transition-colors">{{ $user->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold mt-1 uppercase italic">{{ $user->nomor_identitas ?? 'NIM Tidak Ada' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase italic tracking-tighter border border-blue-100 shadow-sm">
                                            {{ $user->role ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-slate-500 uppercase">{{ $user->prodi ?? '-' }}</span>
                                            <span class="text-[9px] text-slate-400 italic font-bold uppercase tracking-widest mt-0.5">Angkatan {{ $user->angkatan ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <form action="{{ route('shared.anggota.proses-aktivasi') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" onclick="return confirm('Aktivasi anggota ini sekarang?')"
                                                class="inline-flex items-center gap-2 bg-emerald-500 text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-200 transition-all duration-300 mx-auto">
                                                <i class="bi bi-shield-check text-sm"></i> Aktivasi Sekarang
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
