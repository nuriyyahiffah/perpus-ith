<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-8">
                <span class="text-[10px] font-bold uppercase tracking-wider">Digital <span class="text-yellow-400">Library ITH</span></span>
            </div>
            <div class="text-[10px] font-bold uppercase tracking-wider">Notifikasi</div>
        </div>
    </nav>

    <main class="py-12 px-6 flex-grow">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tight">Notifikasi Anda</h1>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">
                        @if($unreadCount > 0)
                            <span class="text-rose-600 font-black">{{ $unreadCount }} notifikasi baru</span>
                        @else
                            Semua notifikasi sudah dibaca
                        @endif
                    </p>
                </div>

                @if($notifications->count() > 0)
                    <div class="flex gap-2">
                        @if($unreadCount > 0)
                            {{-- Perbaikan: Method POST sesuai web.php --}}
                            <form action="{{ route('notifikasi.read-all') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase rounded-lg transition shadow-sm">
                                    <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
                                </button>
                            </form>
                        @endif
                        {{-- Perbaikan: Method DELETE sesuai web.php --}}
                        <form action="{{ route('notifikasi.destroy-all') }}" method="POST" onsubmit="return confirm('Hapus semua notifikasi?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold uppercase rounded-lg transition shadow-sm">
                                <i class="bi bi-trash me-1"></i> Hapus Semua
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold uppercase tracking-wider animate-pulse">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifications List --}}
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border-l-4
                            {{ $notification->tipe == 'success' ? 'border-emerald-500' : ($notification->tipe == 'danger' ? 'border-rose-500' : 'border-blue-500') }} p-6
                            {{ $notification->sudah_dibaca ? 'opacity-60' : 'ring-1 ring-blue-50' }}">

                            <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                                <div class="flex-grow flex gap-4">
                                    {{-- Ikon --}}
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-lg
                                        {{ $notification->tipe == 'success' ? 'bg-emerald-100 text-emerald-600' : ($notification->tipe == 'danger' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600') }}">
                                        <i class="bi {{ $notification->ikon ?? 'bi-bell' }}"></i>
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-black text-[#2D3E50] uppercase tracking-tight text-sm">
                                                {{ $notification->judul }}
                                            </h3>
                                            @if(!$notification->sudah_dibaca)
                                                <span class="inline-block w-2 h-2 bg-rose-500 rounded-full animate-ping"></span>
                                            @endif
                                        </div>
                                        <p class="text-slate-600 text-[12px] leading-relaxed">{{ $notification->pesan }}</p>
                                        <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mt-2">
                                            <i class="bi bi-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                               {{-- Actions --}}
<div class="flex gap-2 flex-shrink-0 self-end sm:self-start">
    @if(!$notification->sudah_dibaca)
        {{-- Tombol Tanda Centang (Mark as Read) --}}
        <form action="{{ route('notifikasi.read', $notification->id) }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-9 h-9 flex items-center justify-center bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl transition border border-emerald-100 group-hover:scale-110 shadow-sm"
                    title="Tandai sudah dibaca">
                <i class="bi bi-check-lg text-lg"></i>
            </button>
        </form>
    @endif

    {{-- Tombol Hapus --}}
    <form action="{{ route('notifikasi.destroy', $notification->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-9 h-9 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-100 group-hover:scale-110 shadow-sm"
                onclick="return confirm('Hapus notifikasi ini?')"
                title="Hapus">
            <i class="bi bi-trash text-sm"></i>
        </button>
    </form>
</div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 shadow-inner">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-bell-slash text-5xl text-slate-200"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mb-2">Tidak ada notifikasi</p>
                    <p class="text-slate-400 text-[10px] italic">Semua update sistem akan muncul di halaman ini.</p>
                </div>
            @endif
        </div>
    </main>

    <footer class="py-8 bg-white border-t border-slate-100 mt-auto">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.3em] mb-1">SIPUSTAKA DIGITAL LIBRARY</p>
            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Institut Teknologi Bacharuddin Jusuf Habibie (ITH)</p>
        </div>
    </footer>

</body>
</html>
