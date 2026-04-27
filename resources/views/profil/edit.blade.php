<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        .form-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02); }
        input:focus, select:focus, textarea:focus { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1); border-color: #3b82f6 !important; }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen flex">
        <main class="flex-1 py-12 px-6 md:px-12">
            <div class="max-w-4xl mx-auto">

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-2 h-8 bg-yellow-400 rounded-full"></div>
                            <h1 class="text-4xl font-extrabold text-[#2D3E50] tracking-tight">
                                Pengaturan <span class="text-blue-600">Profil</span>
                            </h1>
                        </div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] ml-5">
                            Identitas Digital {{ Auth::user()->role }} ITH
                        </p>
                    </div>
                    <a href="{{ url('/') }}" class="flex items-center text-xs font-bold text-slate-500 hover:text-[#2D3E50] transition">
                        <i class="bi bi-arrow-left me-2"></i> KEMBALI KE BERANDA
                    </a>
                </div>

                @if(session('success'))
                <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl mb-8 flex items-center shadow-lg shadow-emerald-200">
                    <i class="bi bi-check-all text-2xl me-3"></i>
                    <span class="text-sm font-bold uppercase tracking-wider">{{ session('success') }}</span>
                </div>
                @endif

                <div class="bg-white rounded-[3rem] overflow-hidden border border-slate-100 form-shadow">
                    <div class="md:flex">

                        <div class="md:w-1/3 bg-[#2D3E50] p-10 text-white flex flex-col justify-between">
                            <div>
                                <div class="w-16 h-16 bg-yellow-400 rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-lg">
                                    <i class="bi bi-person-vcard text-[#2D3E50]"></i>
                                </div>
                                <h3 class="text-xl font-black uppercase leading-tight mb-2">Informasi Akun</h3>
                                <p class="text-[10px] text-slate-400 font-bold leading-relaxed uppercase italic">
                                    Lengkapi nomor WhatsApp Anda untuk menerima notifikasi otomatis terkait peminjaman buku.
                                </p>
                            </div>
                            <div class="text-[9px] font-bold text-slate-500 tracking-widest uppercase italic leading-loose">
                                Digital Library ITH <br> Sistem Informasi Perpustakaan
                            </div>
                        </div>

                        <div class="md:w-2/3 p-8 md:p-14">
                            <form action="{{ route('profil.update') }}" method="POST" class="space-y-8">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4 pb-6 border-b border-slate-100">
                                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Identitas Resmi</h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                                            <div class="px-5 py-3 bg-slate-100 rounded-xl text-slate-400 font-bold text-sm border border-slate-200 cursor-not-allowed italic">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">
                                                {{ Auth::user()->role == 'mahasiswa' ? 'NIM' : 'NIP' }}
                                            </label>
                                            <div class="px-5 py-3 bg-slate-100 rounded-xl text-slate-400 font-bold text-sm border border-slate-200 cursor-not-allowed italic">
                                                {{ $user->nomor_identitas ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Program Studi / Unit</label>
                                        <div class="px-5 py-3 bg-slate-100 rounded-xl text-slate-400 font-bold text-sm border border-slate-200 cursor-not-allowed italic">
                                            {{ $user->prodi ?? 'Umum' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6 pt-2">
                                    <h4 class="text-[11px] font-black text-blue-600 uppercase tracking-widest mb-4">Kontak Notifikasi</h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Email Institusi</label>
                                            <div class="px-5 py-3 bg-blue-50/50 rounded-xl text-blue-700 font-bold text-sm border border-blue-100 cursor-not-allowed italic">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Pribadi</label>
                                            <div class="relative group">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                                    <i class="bi bi-envelope-at"></i>
                                                </div>
                                                <input type="email" name="email_pribadi" value="{{ old('email_pribadi', $user->email_pribadi) }}" placeholder="contoh@gmail.com" class="w-full bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block pl-11 p-4 transition-all">
                                            </div>
                                            @error('email_pribadi') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-[#2D3E50] uppercase tracking-widest ml-1">Nomor WhatsApp (Aktif)</label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">+62</span>
                                                <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="w-full pl-12 pr-5 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 outline-none font-bold text-sm transition-all" placeholder="8123456xxxx">
                                            </div>
                                            @error('no_telp') <p class="text-rose-500 text-[9px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Alamat Tinggal / Domisili</label>
                                        <textarea name="alamat" rows="2" class="w-full px-5 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 outline-none font-bold text-sm transition-all" placeholder="Masukkan alamat lengkap Anda...">{{ old('alamat', $user->alamat) }}</textarea>
                                    </div>
                                </div>

                                {{-- Section Ganti Password --}}
                                <div class="space-y-6 pt-6 border-t border-slate-100">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-[11px] font-black text-rose-500 uppercase tracking-widest italic">Keamanan Akun</h4>
                                        <div class="h-[1px] flex-1 bg-rose-100"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Password Baru</label>
                                            <div class="relative group">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-500">
                                                    <i class="bi bi-shield-lock"></i>
                                                </div>
                                                <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 block pl-11 p-4 transition-all" placeholder="Kosongkan jika tidak diubah">
                                            </div>
                                            @error('password') <p class="text-rose-500 text-[9px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                                            <div class="relative group">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-500">
                                                    <i class="bi bi-shield-check"></i>
                                                </div>
                                                <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 block pl-11 p-4 transition-all" placeholder="Ulangi password baru">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full py-4 bg-yellow-400 hover:bg-yellow-500 text-[#2D3E50] font-black uppercase tracking-[0.2em] text-xs rounded-2xl shadow-xl transition-all border-b-4 border-yellow-600 active:translate-y-1 active:border-b-0">
                                        <i class="bi bi-save2 me-2"></i> Simpan Perubahan Profil
                                    </button>
                                </div>
                            </form>

                            {{-- Bantuan WhatsApp Admin --}}
                            <div class="mt-8 text-center border-t border-slate-50 pt-6">
                                <a href="https://wa.me/628XXXXXXXXXX?text=Halo%20Admin%20SIPUSTAKA,%20saya%20lupa%20password%20dengan%20NIM:%20{{ $user->nomor_identitas }}" 
                                   target="_blank" class="text-[10px] font-black text-slate-400 hover:text-blue-600 transition uppercase tracking-[0.2em]">
                                   Lupa Password? <span class="text-blue-500">Hubungi Admin via WhatsApp</span>
                                </a>
                            </div>
                        </div> 
                    </div>
                </div>

                <footer class="mt-12 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.5em]">&copy; 2026 Institut Teknologi Bacharuddin Jusuf Habibie</p>
                </footer>

            </div>
        </main>
    </div>

</body>
</html>