<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anggota - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area {
                position: fixed; left: 0; top: 0; width: 100%;
                display: flex !important; justify-content: center;
                padding-top: 80px; background: white;
            }
            @page { size: landscape; margin: 0; }
        }
        .card-gradient { background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%); }
        /* Scrollbar halus untuk sidebar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1e293b; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>

<body class="bg-[#F1F5F9] antialiased" x-data="anggotaTable()">

    <div class="flex min-h-screen">

        @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <main class="p-6 lg:p-10">
                <header class="flex justify-between items-center mb-10">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                            Data <span class="text-blue-600">Anggota</span>
                        </h1>
                        <p class="text-slate-500 text-[10px] lg:text-xs mt-2 font-semibold italic uppercase tracking-widest">Manajemen Member Aktif Perpustakaan</p>
                    </div>
                </header>

                <div class="flex flex-col md:flex-row gap-4 mb-8">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                            <i class="bi bi-search text-sm"></i>
                        </span>
                        <input type="text" x-model="search"
                            class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-14 pr-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none shadow-sm transition-all"
                            placeholder="Cari Nama atau Nomor Member...">
                    </div>

                    <div class="relative w-full md:w-72">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                            <i class="bi bi-filter-circle-fill text-lg"></i>
                        </span>
                        <select x-model="filterVal"
                            class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-14 pr-10 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none shadow-sm appearance-none cursor-pointer transition-all">
                            <option value="name_asc">Nama A - Z</option>
                            <option value="name_desc">Nama Z - A</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-5 top-5 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 text-center w-20 tracking-widest">No</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Informasi Anggota</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center tracking-widest">Aksi Cetak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50" x-cloak>
                                <template x-for="(agt, index) in filteredAnggota" :key="agt.id">
                                    <tr class="hover:bg-blue-50/30 transition duration-200 group">
                                        <td class="px-6 py-6 text-center text-xs font-black text-slate-300 group-hover:text-blue-500" x-text="index + 1"></td>
                                        <td class="px-8 py-6">
                                            <div class="flex flex-col">
                                                <span class="font-extrabold text-slate-700 text-sm uppercase tracking-tight group-hover:text-blue-600 transition-colors" x-text="agt.user?.name || 'User Tidak Ditemukan'"></span>
                                                <span class="text-[10px] text-slate-400 font-bold mt-1 group-hover:text-slate-500" x-text="'ID: ' + agt.nomor_anggota"></span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <button @click="printCard(agt)"
                                                class="group/btn inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-emerald-500 hover:text-white transition-all duration-300 border border-slate-100 hover:border-emerald-500 shadow-sm">
                                                <i class="bi bi-printer-fill text-sm"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest">Cetak Kartu</span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div id="print-area" class="hidden">
        <div class="w-[450px] h-[260px] rounded-[2.5rem] card-gradient text-white p-7 relative shadow-2xl border-[6px] border-white/10 flex flex-col justify-between overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            <div class="flex items-center gap-4 border-b border-white/20 pb-4 relative z-10">
                <div class="bg-white p-2 rounded-2xl text-blue-800 shadow-xl"><i class="bi bi-patch-check-fill text-3xl"></i></div>
                <div>
                    <h2 class="text-[11px] font-black uppercase tracking-[0.2em] leading-none text-blue-200">Kartu Anggota</h2>
                    <p class="text-sm font-black mt-1 tracking-tighter uppercase">PERPUSTAKAAN ITH</p>
                </div>
            </div>
            <div class="flex gap-6 items-center relative z-10">
                <div class="w-20 h-24 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center backdrop-blur-md">
                    <i class="bi bi-person-bounding-box text-5xl opacity-30"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[8px] opacity-60 font-black uppercase tracking-widest">Nama Lengkap</p>
                    <p id="p-name" class="font-black text-sm uppercase mb-2 truncate w-full"></p>
                    <p class="text-[8px] opacity-60 font-black uppercase tracking-widest">ID Anggota</p>
                    <p id="p-id" class="font-black text-sm tracking-widest text-yellow-400"></p>
                    <div class="flex gap-6 mt-2">
                        <div>
                            <p class="text-[8px] opacity-60 font-black">Prodi</p>
                            <p id="p-prodi" class="font-bold text-[10px] uppercase"></p>
                        </div>
                        <div>
                            <p class="text-[8px] opacity-60 font-black">Angkatan</p>
                            <p id="p-year" class="font-bold text-[10px] uppercase"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function anggotaTable() {
            return {
                search: '',
                filterVal: 'name_asc',
                allData: @json($anggota),
                get filteredAnggota() {
                    if (!this.allData) return [];
                    let data = [...this.allData];
                    if (this.search !== '') {
                        const s = this.search.toLowerCase();
                        data = data.filter(item => {
                            const name = item.user?.name?.toLowerCase() || '';
                            const nomor = item.nomor_anggota?.toLowerCase() || '';
                            return name.includes(s) || nomor.includes(s);
                        });
                    }
                    if (this.filterVal === 'name_asc') {
                        data.sort((a, b) => (a.user?.name || '').localeCompare(b.user?.name || ''));
                    } else if (this.filterVal === 'name_desc') {
                        data.sort((a, b) => (b.user?.name || '').localeCompare(a.user?.name || ''));
                    }
                    return data;
                },
                printCard(agt) {
                    document.getElementById('p-name').innerText = agt.user?.name || '-';
                    document.getElementById('p-id').innerText = agt.nomor_anggota || '-';
                    document.getElementById('p-prodi').innerText = agt.user?.prodi || '-';
                    document.getElementById('p-year').innerText = agt.user?.angkatan || '-';
                    window.print();
                }
            }
        }
    </script>
</body>
</html>
