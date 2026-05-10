<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - SIPUSTAKA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        .bg-main-setting {
            background: radial-gradient(circle at top right, #f8fafc, #eff6ff);
        }

        .setting-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .card-setting {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-setting:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-main-setting">

    <div class="container mx-auto px-6 py-8 min-h-screen" x-data="{ tab: 'profil' }">
        
        {{-- HEADER --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-5">
                <div class="w-14 h-14 bg-[#2D3E50] rounded-2xl flex items-center justify-center text-white shadow-xl rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="bi bi-gear-wide-connected text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-[#2D3E50] uppercase tracking-tighter">Pengaturan Sistem</h1>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-[0.3em]">Pusat Kendali <span class="text-blue-500">SIPUSTAKA</span></p>
                </div>
            </div>
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    class="bg-white border-l-4 border-emerald-500 text-emerald-700 px-6 py-4 rounded-xl shadow-lg flex items-center">
                    <i class="bi bi-patch-check-fill me-3 text-xl text-emerald-500"></i>
                    <span class="text-xs font-black uppercase tracking-wider">{{ session('success') }}</span>
                </div>
            @endif
        </div>

        {{-- NAVIGASI TAB --}}
        <div class="flex space-x-3 bg-white/60 backdrop-blur-md p-2 rounded-3xl mb-10 w-fit border border-slate-200/50 shadow-sm">
            <button @click="tab = 'profil'" :class="tab === 'profil' ? 'bg-[#2D3E50] text-white shadow-lg scale-105' : 'text-slate-500 hover:bg-white'" class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center">
                <i class="bi bi-building-fill-gear me-2"></i> Profil
            </button>

            <button @click="tab = 'api'" :class="tab === 'api' ? 'bg-[#2D3E50] text-white shadow-lg scale-105' : 'text-slate-500 hover:bg-white'" class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center">
                <i class="bi bi-cpu-fill me-2"></i> API & Notifikasi
            </button>

            <button @click="tab = 'backup'" :class="tab === 'backup' ? 'bg-[#2D3E50] text-white shadow-lg scale-105' : 'text-slate-500 hover:bg-white'" class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center">
                <i class="bi bi-database-fill-down me-2"></i> Backup Data
            </button>
        </div>

        <form action="{{ route('shared.setting.update') }}" method="POST">
            @csrf
            
            {{-- TAB: PROFIL --}}
            <div x-show="tab === 'profil'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden card-setting">
                    <div class="p-8 border-b border-slate-50 bg-gradient-to-r from-slate-50 to-white">
                        <h3 class="font-black text-[#2D3E50] uppercase text-xs tracking-[0.3em] flex items-center">
                            <span class="w-8 h-1 bg-blue-500 rounded-full me-3"></span> Identitas Instansi
                        </h3>
                    </div>
                    <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center"><i class="bi bi-bookmark-star me-2"></i> Nama Perpustakaan</label>
                            <input type="text" name="nama_perpus" value="{{ $settings['nama_perpus'] ?? '' }}" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl setting-input outline-none transition font-bold text-slate-700">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center"><i class="bi bi-person-badge me-2"></i> Kepala Perpustakaan</label>
                            <input type="text" name="kepala_perpus" value="{{ $settings['kepala_perpus'] ?? '' }}" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl setting-input outline-none transition font-bold text-slate-700">
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center"><i class="bi bi-geo-alt me-2"></i> Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl setting-input outline-none transition font-bold text-slate-700">{{ $settings['alamat'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: API & NOTIFIKASI --}}
            <div x-show="tab === 'api'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden card-setting">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="font-black text-[#2D3E50] uppercase text-xs tracking-[0.3em] flex items-center">
                            <span class="w-8 h-1 bg-emerald-500 rounded-full me-3"></span> WhatsApp Gateway & Automasi
                        </h3>
                    </div>
                    <div class="p-10 space-y-8">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-500 p-8 rounded-[2rem] text-white shadow-lg shadow-emerald-200 relative overflow-hidden">
                            <i class="bi bi-whatsapp absolute right-[-20px] top-[-20px] text-[150px] opacity-10 rotate-12"></i>
                            <div class="relative z-10">
                                <h4 class="font-black uppercase text-xs tracking-widest mb-2">Integrasi Fonnte</h4>
                                <p class="text-xs opacity-90 leading-relaxed font-medium">Gunakan API Token dari dashboard Fonnte untuk mengaktifkan fitur pengiriman pesan otomatis.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block px-1">API Key / Token</label>
                            <input type="password" name="wa_token" value="{{ $settings['wa_token'] ?? '' }}" class="w-full px-8 py-5 bg-slate-50 border border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-50 outline-none font-mono text-slate-600 tracking-widest shadow-inner">
                        </div>

                        <hr class="border-slate-100">

                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block px-1">Fitur Notifikasi Otomatis</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <i class="bi bi-alarm text-blue-500 text-lg"></i>
                                        <span class="text-xs font-bold text-slate-700">Kirim Pengingat Sebelum</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="reminder_days" value="{{ $settings['reminder_days'] ?? 1 }}" 
                                            class="w-20 px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-slate-700 text-center">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Hari Jatuh Tempo</span>
                                    </div>
                                </div>
                                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-3">
    <div class="flex items-center space-x-3">
        <i class="bi bi-clock-history text-blue-500 text-lg"></i>
        <span class="text-xs font-bold text-slate-700">Waktu Pengiriman Notif</span>
    </div>
    <div class="flex items-center gap-3">
        <input type="time" name="notif_time" value="{{ $settings['notif_time'] ?? '08:00' }}" 
            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-slate-700">
    </div>
    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter italic">*Notifikasi otomatis akan dikirim pada jam ini</p>
</div>

                                <div class="flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-slate-100">
                                    <div class="flex items-center space-x-3">
                                        <i class="bi bi-chat-left-check text-emerald-500 text-lg"></i>
                                        <div>
                                            <span class="text-xs font-bold text-slate-700 block">Notifikasi Kembali</span>
                                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter">Kirim bukti saat buku dikembalikan</span>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="notif_return" value="1" class="sr-only peer" {{ ($settings['notif_return'] ?? '') == '1' ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: BACKUP DATA --}}
            <div x-show="tab === 'backup'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden card-setting">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="font-black text-[#2D3E50] uppercase text-xs tracking-[0.3em] flex items-center">
                            <span class="w-8 h-1 bg-amber-500 rounded-full me-3"></span> Pemeliharaan Database
                        </h3>
                    </div>
                    <div class="p-10">
                        <div class="flex items-center gap-8 bg-amber-50 p-8 rounded-[2rem] border border-amber-100">
                            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center text-amber-500 shadow-sm shrink-0">
                                <i class="bi bi-shield-lock-fill text-4xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-amber-900 uppercase tracking-wide mb-1">Backup Database Berkala</h4>
                                <p class="text-xs text-amber-800/70 leading-relaxed font-medium">Amankan data peminjaman, buku, dan anggota SIPUSTAKA secara rutin. File backup akan diunduh dalam format <span class="font-bold">.sql</span>.</p>
                            </div>
                        </div>

                        <div class="mt-10 flex flex-col items-center justify-center py-10 border-2 border-dashed border-slate-100 rounded-[2rem]">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Klik tombol di bawah untuk mulai ekspor</p>
                            
                            <a href="{{ route('shared.backup.download') }}" class="flex items-center gap-3 bg-white border-2 border-[#2D3E50] text-[#2D3E50] px-10 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-[#2D3E50] hover:text-white transition-all duration-300 shadow-lg shadow-slate-200">
                                <i class="bi bi-cloud-arrow-down-fill text-lg"></i>
                                Unduh Database (.sql)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTON (Disembunyikan jika di tab backup agar tidak bingung) --}}
            <div class="mt-12 flex justify-end" x-show="tab !== 'backup'">
                <button type="submit" class="group relative overflow-hidden bg-[#2D3E50] text-white px-16 py-5 rounded-[2rem] font-black text-[12px] uppercase tracking-[0.3em] transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-blue-900/30">
                    <div class="relative z-10 flex items-center">
                        <i class="bi bi-save2-fill me-3 text-lg animate-bounce"></i>
                        Simpan Perubahan
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                </button>
            </div>
        </form>
    </div>

</body>
</html>