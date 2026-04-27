<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peminjaman - SIPUSTAKA</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        body { background-color: #f0f2f5; padding-top: 30px; font-family: 'Segoe UI', sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .card-header { border-radius: 15px 15px 0 0 !important; background: linear-gradient(45deg, #0d6efd, #0043a8); }
        .info-box { display: none; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 10px; padding: 15px; }
        .select2-container--bootstrap-5 .select2-selection { border-radius: 10px; height: 45px; display: flex; align-items: center; }
        .table thead { background-color: #f8f9fa; }
        .btn-remove { color: #dc3545; transition: 0.2s; border: none; background: none; }
        .btn-remove:hover { color: #a71d2a; transform: scale(1.1); }
    </style>
</head>
<body>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Form Transaksi Peminjaman</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('shared.peminjaman.store') }}" method="POST" id="formPeminjaman">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Peminjam (Mahasiswa/Siswa)</label>
                            <div class="input-group">
                                <input type="text" id="keyword_user" class="form-control" placeholder="Masukkan NIM atau Nama..." style="height: 45px;">
                                <button class="btn btn-primary px-4" type="button" id="btnCekUser">
                                    <i class="fas fa-search me-1"></i> Cek Data
                                </button>
                            </div>
                            <input type="hidden" name="user_id" id="user_id_hidden">
                            
                            <div id="user_info_box" class="info-box mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 id="res_name" class="fw-bold mb-0 text-dark">-</h6>
                                        <small id="res_nim" class="text-muted">-</small>
                                    </div>
                                    <span id="res_status" class="badge rounded-pill px-3 bg-success">AKTIF</span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Cari & Tambah Buku</label>
                            <div class="alert alert-info py-2 mb-3" style="font-size: 0.85rem; border-left: 4px solid #0dcafb;">
                                <i class="fas fa-info-circle me-1"></i> Pilih judul buku untuk memunculkan pilihan No. Induk fisik yang tersedia.
                            </div>
                            <select id="search_buku_ajax" class="form-select"></select>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tabel-pinjam">
                                <thead class="text-center">
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th width="130px">Stok Tersedia</th>
                                        <th width="280px">No. Induk (Fisik Buku)</th>
                                        <th width="60px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="list_buku_pinjam">
                                    <tr id="empty_row">
                                        <td colspan="4" class="text-center text-muted py-4 fst-italic">Belum ada buku ditambahkan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Tanggal Pinjam</label>
                                <input type="text" class="form-control bg-light" value="{{ date('d/m/Y') }}" readonly style="height: 45px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Tenggat Pengembalian</label>
                                <input type="date" name="tgl_kembali" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required style="height: 45px;">
                                <small class="text-muted">Standar peminjaman adalah 7 hari.</small>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('shared.peminjaman.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" id="btnSimpan" class="btn btn-primary btn-lg px-5 fw-bold" disabled>
                                <i class="fas fa-save me-2"></i> Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    
    const MAX_PINJAM = 3;

    // 1. Logika Cek Mahasiswa
    $('#btnCekUser').click(function() {
        let kw = $('#keyword_user').val();
        if(kw.length < 3) return alert('Ketik minimal 3 karakter untuk pencarian');
        
        $.get("{{ route('shared.getUsers') }}", { nim: kw }, function(res) {
            if(res.success) {
                $('#user_id_hidden').val(res.id);
                $('#res_name').text(res.name);
                $('#res_nim').text('Identitas: ' + res.nomor_identitas);
                $('#user_info_box').fadeIn();
                validateForm();
            } else {
                alert('Peminjam tidak ditemukan!');
                $('#user_id_hidden').val('');
                $('#user_info_box').hide();
                validateForm();
            }
        });
    });

    // 2. Select2 Buku
    $('#search_buku_ajax').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik judul buku di sini...',
        minimumInputLength: 1,
        ajax: {
            url: "{{ route('shared.getBooks') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return { results: data };
            }
        }
    });

    // 3. Ketika Buku Dipilih
    $('#search_buku_ajax').on('select2:select', function (e) {
        let data = e.params.data;
        let currentRows = $('#list_buku_pinjam tr').not('#empty_row').length;

        if (currentRows >= MAX_PINJAM) {
            alert('Maksimal peminjaman adalah ' + MAX_PINJAM + ' buku.');
            $(this).val(null).trigger('change');
            return;
        }

        if ($(`#row_${data.id}`).length > 0) {
            alert('Buku ini sudah ada dalam daftar.');
            $(this).val(null).trigger('change');
            return;
        }

        $('#empty_row').hide();
        let uniqueSelectId = 'select_no_induk_' + data.id;

        // PERBAIKAN: Menghapus data.sinopsis yang menyebabkan error
        let row = `
            <tr id="row_${data.id}">
                <td>
                    <div class="fw-bold text-dark">${data.text}</div>
                    <input type="hidden" name="buku_id[]" value="${data.id}">
                </td>
                <td class="text-center">
                    <span class="badge bg-light text-primary border border-primary">${data.stok} Eks</span>
                </td>
                <td>
                    <select name="no_induk_id[]" class="form-select form-select-sm" id="${uniqueSelectId}" required>
                        <option value="">Memuat No. Induk...</option>
                    </select>                
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-remove btn-sm" onclick="removeRow('${data.id}')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#list_buku_pinjam').append(row);

        // Ambil Eksemplar
        let urlEksemplar = "{{ route('shared.getEksemplar', ':id') }}".replace(':id', data.id);

        $.get(urlEksemplar, function(res) {
            let options = '<option value="">-- Pilih No. Induk --</option>';
            if(res.length > 0) {
                res.forEach(function(item) {
                    options += `<option value="${item.id}">${item.no_induk}</option>`;
                });
            } else {
                options = '<option value="">Stok fisik tidak tersedia</option>';
            }
            $(`#${uniqueSelectId}`).html(options);
        });

        $(this).val(null).trigger('change');
        validateForm();
    });

    window.validateForm = function() {
        let userSet = $('#user_id_hidden').val() !== "";
        let hasBooks = $('#list_buku_pinjam tr').not('#empty_row').length > 0;
        $('#btnSimpan').prop('disabled', !(userSet && hasBooks));
    };

    window.removeRow = function(id) {
        $(`#row_${id}`).remove();
        if ($('#list_buku_pinjam tr').not('#empty_row').length === 0) {
            $('#empty_row').show();
        }
        validateForm();
    };
});
</script>
</body>
</html>