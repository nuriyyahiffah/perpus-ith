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
<body class="antialiased">

    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-8">
                <span class="text-[10px] font-bold uppercase tracking-wider">Digital <span class="text-yellow-400">Library ITH</span></span>
            </div>
            <div class="text-[10px] font-bold uppercase tracking-wider">Notifikasi</div>
        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="mb-8 flex justify-between items-center">
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
                            <form action="{{ route('notifikasi.read-all') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase rounded-lg transition">
                                    <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notifikasi.destroy-all') }}" method="POST" class="inline" onsubmit="return confirm('Hapus semua notifikasi?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold uppercase rounded-lg transition">
                                <i class="bi bi-trash me-1"></i> Hapus Semua
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Alert Success/Error --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold uppercase tracking-wider">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifications List --}}
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="group bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow border-l-4 {{ $notification->getBadgeColor() }} p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-grow">
                                    {{-- Notification Icon & Title --}}
                                    <div class="flex items-start gap-4 mb-2">
                                        <div class="w-10 h-10 rounded-lg {{ str_replace(['bg-'], ['bg-opacity-20 bg-'], $notification->getColorClass()) }} flex items-center justify-center flex-shrink-0 text-lg">
                                            <i class="bi {{ $notification->icon }}"></i>
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-black text-[#2D3E50] uppercase tracking-tight">
                                                    {{ $notification->title }}
                                                </h3>
                                                @if(!$notification->read)
                                                    <span class="inline-block w-2 h-2 {{ $notification->getBadgeColor() }} rounded-full"></span>
                                                @endif
                                            </div>
                                            <p class="text-slate-600 text-[12px] leading-relaxed">
                                                {{ $notification->message }}
                                            </p>
                                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-2">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex gap-2 flex-shrink-0">
                                    @if($notification->action_url)
                                        <form action="{{ route('notifikasi.read', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-[9px] font-bold uppercase rounded-lg transition">
                                                <i class="bi bi-arrow-right"></i> Buka
                                            </button>
                                        </form>
                                    @elseif(!$notification->read)
                                        <form action="{{ route('notifikasi.read', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-[9px] font-bold uppercase rounded-lg transition">
                                                <i class="bi bi-check"></i> Tandai Dibaca
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('notifikasi.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-[9px] font-bold uppercase rounded-lg transition">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-bell-slash text-5xl text-slate-300"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mb-2">Tidak ada notifikasi</p>
                    <p class="text-slate-400 text-xs italic">Notifikasi akan muncul di sini ketika ada update penting</p>
                </div>
            @endif
        </div>
    </main>

    <footer class="py-8 bg-white border-t border-slate-100 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-[#2D3E50] uppercase tracking-[0.3em] mb-1">SIPUSTAKA DIGITAL LIBRARY</p>
            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Institut Teknologi Bacharuddin Jusuf Habibie (ITH)</p>
        </div>
    </footer>

</body>
</html>
