<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: sans-serif; font-size: 10px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 6px 4px; word-wrap: break-word; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; text-transform: uppercase; }
        
        .text-center { text-align: center; }
        .footer { margin-top: 30px; float: right; width: 250px; text-align: center; font-size: 11px; }
        
        /* Pengaturan lebar kolom agar rapi di Landscape */
        .col-no { width: 30px; }
        .col-tgl { width: 80px; }
        .col-nama { width: 150px; }
        .col-judul { width: 200px; }
        .col-induk { width: 90px; }
        .col-status { width: 70px; }
        .col-denda { width: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $nama_perpus }}</h2>
        <p>LAPORAN PEMINJAMAN BUKU BULANAN</p>
        <p>Periode: {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-tgl">Tgl Pinjam</th>
                <th class="col-induk">No. Induk</th>
                <th class="col-judul">Judul Buku</th>
                <th class="col-nama">Nama Anggota</th>
                <th class="col-tgl">Tgl Kembali</th>
                <th class="col-status">Status</th>
                <th class="col-denda">Denda</th>
            </tr>
        </thead>
        <tbody>
    @forelse($data as $index => $item)
    <tr>
        <td class="text-center">{{ $index + 1 }}</td>
        <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
        
        {{-- TAMPILKAN NO INDUK DARI EKSEMPLAR --}}
        <td class="text-center">{{ $item->eksemplar->no_induk ?? '-' }}</td>
        
        {{-- TAMPILKAN JUDUL DARI RELASI BUKU DI DALAM EKSEMPLAR --}}
        <td>{{ $item->eksemplar->buku->judul ?? '-' }}</td>
        
        <td>{{ $item->user->name }}</td>
        <td class="text-center">
            {{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') : '-' }}
        </td>
        <td class="text-center">{{ ucfirst($item->status) }}</td>
        <td class="text-center">Rp{{ number_format($item->denda_fisik, 0, ',', '.') }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center">Tidak ada data transaksi pada periode ini.</td>
    </tr>
    @endforelse
</tbody>
    </table>

    <div class="footer">
        <p>Parepare, {{ $tgl_cetak }}</p>
        <br><br><br><br>
        <p><b>( Petugas Perpustakaan )</b></p>
    </div>
</body>
</html>