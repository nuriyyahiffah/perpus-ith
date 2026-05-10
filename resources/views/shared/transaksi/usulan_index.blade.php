<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Usulan Buku - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }</style>
</head>
<body class="antialiased">

    {{-- NAVBAR SEDERHANA --}}
    <nav class="bg-[#1E293B]/90 backdrop-blur-md text-white py-4 sticky top-0 z-50 border-b border-white/10">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-10 brightness-110">
                <div class="hidden sm:block border-l border-white/20 pl-4">
                    <span class="text-[11px] font-black leading-none uppercase tracking-tighter block">
                        Manajemen<br><span class="text-yellow-400">Usulan Buku ITH</span>
                    </span>
                </div>
            </div>
        <a href="{{ 
    Auth::user()->role === 'admin' ? route('admin.dashboard') : 
    (Auth::user()->role === 'pustakawan' ? route('pustakawan.dashboard') : 
    (in_array(Auth::user()->role, ['dosen', 'kaprodi']) ? route('dosen.beranda') : 
    route('mahasiswa.beranda'))) 
}}" class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-300 hover:text-white transition">
    Dashboard
</a>  </div>
    </nav>

    <div class="container mx-auto py-10 px-6">
        <div class="mb-10">
            <h2 class="text-3xl font-extrabold text-[#1A2B3C] uppercase tracking-tighter">Rekap <span class="text-indigo-600 italic">Usulan Pengadaan</span></h2>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1">Daftar permintaan buku dari Dosen & Mahasiswa</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl shadow-sm text-xs font-bold text-emerald-800 uppercase">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5">Pengusul</th>
                        <th class="px-8 py-5">Informasi Buku</th>
                        <th class="px-8 py-5">Alasan Kebutuhan</th>
                        <th class="px-8 py-5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatUsulan as $u)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        {{-- KOLOM PENGUSUL --}}
                        <td class="px-8 py-6">
                            <p class="text-xs font-black text-slate-800 uppercase">{{ $u->user->name }}</p>
                            <span class="text-[9px] px-2 py-0.5 rounded-md font-black uppercase {{ $u->user->role == 'dosen' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                                {{ $u->user->role }}
                            </span>
                            <p class="text-[9px] text-slate-400 mt-1 font-bold">{{ $u->created_at->format('d/m/Y') }}</p>
                        </td>

                        {{-- KOLOM DETAIL BUKU --}}
                        <td class="px-8 py-6">
                            <p class="text-xs font-black text-slate-800 uppercase leading-tight">{{ $u->judul }}</p>
                            <p class="text-[10px] text-indigo-600 font-bold italic">{{ $u->penulis }} ({{ $u->tahun ?? '-' }})</p>
                        </td>

                        {{-- KOLOM ALASAN --}}
                        <td class="px-8 py-6">
                            <div class="max-w-xs text-[10px] text-slate-500 normal-case leading-relaxed italic border-l-2 border-slate-200 pl-3">
                                "{{ $u->alasan }}"
                            </div>
                        </td>

                        {{-- KOLOM AKSI --}}
                        <td class="px-8 py-6">
                            <form action="{{ route('shared.usulan.konfirmasi', $u->id) }}" method="POST" class="flex flex-col gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="text-[9px] font-black uppercase border-none rounded-xl px-4 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500
                                    {{ $u->status == 'pending' ? 'bg-amber-50 text-amber-600' : '' }}
                                    {{ $u->status == 'disetujui' ? 'bg-emerald-50 text-emerald-600' : '' }}
                                    {{ $u->status == 'ditolak' ? 'bg-rose-50 text-rose-600' : '' }}">
                                    <option value="pending" {{ $u->status == 'pending' ? 'selected' : '' }}>⌛ Diproses</option>
                                    <option value="disetujui" {{ $u->status == 'disetujui' ? 'selected' : '' }}>✅ Setujui</option>
                                    <option value="ditolak" {{ $u->status == 'ditolak' ? 'selected' : '' }}>❌ Tolak</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <i class="bi bi-mailbox text-4xl text-slate-200 block mb-2"></i>
                            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest italic">Belum ada usulan buku masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
