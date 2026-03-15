<?= $this->extend('layout/clients') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <nav aria-label="breadcrumb" class="mb-3 animate__animated animate__fadeIn">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('client') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Form Pengajuan</li>
                </ol>
            </nav>

            <div class="card shadow-pro border-0 animate__animated animate__fadeInUp" style="border-radius: 20px;">
                <div class="card-header bg-gradient-success text-white py-4 border-0">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="icon-box me-3">
                            <i class="fas fa-file-signature fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">PENGAJUAN BARANG</h4>
                            <p class="small mb-0 opacity-75">Lengkapi formulir untuk permintaan barang baru</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger border-0 shadow-sm animate__animated animate__shakeX">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('client/store') ?>" method="post" id="formPengajuan">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-box-open me-1 text-success"></i> Cari & Pilih Barang
                            </label>
                            <select name="barang_id" id="selectBarang" class="form-select custom-select" required>
                                <option value=""></option> 
                                <?php foreach($list_barang as $b): ?>
                                    <option value="<?= $b['id'] ?>" data-stok="<?= $b['stok'] ?>" data-satuan="<?= $b['satuan'] ?? 'Unit' ?>">
                                        <?= $b['nama_barang'] ?> — [<?= $b['kategori'] ?>]
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="stok-info" class="mt-2" style="display:none;">
                                <div class="badge rounded-pill bg-light text-success border border-success px-3 py-2">
                                    <i class="fas fa-check-circle"></i> Stok Tersedia: <span id="jml-stok" class="fw-bold">0</span> <span id="satuan-barang"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-tags me-1 text-success"></i> Jenis Pengajuan
                                </label>
                                <select name="jenis" id="selectJenis" class="form-select" required>
                                    
                                    <option value="minta">🎁 Minta (Pakai Sendiri)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-layer-group me-1 text-success"></i> Jumlah
                                </label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-sort-numeric-up text-muted"></i></span>
                                    <input type="number" name="qty" id="inputQty" class="form-control border-start-0 ps-0" min="1" placeholder="Masukkan jumlah..." required>
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-comment-dots me-1 text-success"></i> Keperluan / Alasan
                                </label>
                                <textarea name="keperluan" class="form-control" rows="3" placeholder="Jelaskan alasan pengajuan Anda secara detail..." required style="border-radius: 12px;"></textarea>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-6 col-md-5">
                                <a href="<?= base_url('client') ?>" class="btn btn-light-pro w-100 py-3 fw-bold">
                                    <i class="fas fa-arrow-left me-2"></i> KEMBALI
                                </a>
                            </div>
                            <div class="col-6 col-md-7">
                                <button type="submit" id="btnKirim" class="btn btn-success-pro w-100 py-3 fw-bold shadow-sm">
                                    <i class="fas fa-paper-plane me-2"></i> KIRIM PENGAJUAN
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#selectBarang').select2({
        theme: 'bootstrap-5',
        placeholder: "🔍 Ketik nama barang...",
        allowClear: true,
        width: '100%'
    });

    // Event saat barang dipilih
    $('#selectBarang').on('change', function() {
        const selected = $(this).find(':selected');
        const stok = parseInt(selected.data('stok'));
        const satuan = selected.data('satuan');
        
        if(stok >= 0) {
            $('#jml-stok').text(stok);
            $('#satuan-barang').text(satuan);
            $('#inputQty').attr('max', stok);
            $('#stok-info').hide().fadeIn();
            
            if(stok === 0) {
                $('#inputQty').val(0).prop('disabled', true);
                Swal.fire('Oops!', 'Stok barang ini sedang kosong.', 'warning');
            } else {
                $('#inputQty').prop('disabled', false);
            }
        } else {
            $('#stok-info').fadeOut();
        }
    });

    // Validasi Qty saat input
    $('#inputQty').on('input', function() {
        const max = parseInt($(this).attr('max'));
        const val = parseInt($(this).val());
        if(val > max) {
            Swal.fire('Peringatan', 'Jumlah tidak boleh melebihi stok tersedia!', 'error');
            $(this).val(max);
        }
    });

    // Intercept Submit untuk Loading State
    $('#formPengajuan').on('submit', function() {
        const qty = $('#inputQty').val();
        if(qty <= 0) {
            Swal.fire('Eror', 'Jumlah minimal pengajuan adalah 1', 'error');
            return false;
        }
        
        $('#btnKirim').html('<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...').prop('disabled', true);
    });
});
</script>

<style>
    /* Custom Styling Pro */
    :root {
        --success-gradient: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }

    body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }

    .shadow-pro { box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important; }
    
    .bg-gradient-success { background: var(--success-gradient); }

    .icon-box {
        background: rgba(255,255,255,0.2);
        padding: 15px;
        border-radius: 15px;
        backdrop-filter: blur(5px);
    }

    /* Input Styling */
    .custom-input-group .form-control {
        border-radius: 12px;
        padding: 12px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    .custom-input-group .form-control:focus {
        background-color: #fff;
        box-shadow: none;
        border-color: #28a745;
    }

    /* Select2 Bootstrap 5 Theme Overrides */
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 12px !important;
        min-height: 50px !important;
        display: flex !important;
        align-items: center !important;
        background-color: #f8f9fa !important;
    }

    /* Button Styling */
    .btn-success-pro {
        background: var(--success-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        transition: all 0.3s;
    }
    .btn-success-pro:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .btn-light-pro {
        background: #fff;
        color: #6c757d;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        transition: all 0.3s;
    }
    .btn-light-pro:hover {
        background: #f8f9fa;
        color: #333;
    }

    @media (max-width: 576px) {
        .card-header h4 { font-size: 1.2rem; }
        .p-md-5 { padding: 1.5rem !important; }
    }
</style>

<?= $this->endSection() ?>