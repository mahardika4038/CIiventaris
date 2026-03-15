<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-12 col-md-6 col-lg-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="<?= base_url('lokasi') ?>" class="text-decoration-none">Lokasi</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= isset($row) ? 'Edit' : 'Tambah' ?></li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h4 class="fw-bold mb-1">
                    <i class="fas fa-<?= isset($row) ? 'pen-to-square' : 'plus-circle' ?> text-primary me-2"></i>
                    <?= isset($row) ? 'Edit Lokasi' : 'Tambah Lokasi' ?>
                </h4>
                <p class="text-muted small">Tentukan nama ruangan atau area penempatan aset.</p>
            </div>

            <div class="card-body p-4">
                <form method="post" action="<?= base_url(isset($row) ? 'lokasi/update/' . $row['id'] : 'lokasi/store') ?>" id="formLokasi">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 1px;">Nama Lokasi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="fas fa-map-location-dot"></i>
                            </span>
                            <input type="text" 
                                   name="nama_lokasi" 
                                   class="form-control form-control-lg bg-light border-start-0" 
                                   style="font-size: 1rem;"
                                   placeholder="Contoh: Lab Komputer, Gudang Utama..." 
                                   value="<?= isset($row) ? esc($row['nama_lokasi']) : '' ?>" 
                                   required 
                                   autofocus>
                        </div>
                        <div class="form-text mt-2" style="font-size: 11px;">
                            *Gunakan nama yang spesifik untuk mempermudah pencarian aset.
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 mt-2">
                        <a href="<?= base_url('lokasi') ?>" class="btn btn-light rounded-pill px-4 py-2 order-2 order-sm-1 flex-grow-1">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm order-1 order-sm-2 flex-grow-1" id="btnSubmit">
                            <i class="fas fa-save me-2"></i> <?= isset($row) ? 'Update Data' : 'Simpan Lokasi' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i> Data lokasi ini akan muncul di pilihan saat menginput barang baru.
            </small>
        </div>
    </div>
</div>

<script>
    // Efek Loading sederhana saat tombol ditekan
    const form = document.getElementById('formLokasi');
    const btn = document.getElementById('btnSubmit');

    form.addEventListener('submit', function() {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';
        btn.classList.add('disabled');
    });
</script>

<?= $this->endSection() ?>