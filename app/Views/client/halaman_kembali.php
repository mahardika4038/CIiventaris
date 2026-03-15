<?= $this->extend('layout/clients') ?>
<?= $this->section('content') ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card border-0 shadow-lg p-4 text-center" style="border-radius: 20px; max-width: 400px; width: 100%; border-top: 10px solid #198754;">
        <div class="mb-4">
            <div class="rounded-circle bg-success text-white mx-auto d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                <i class="fas fa-undo-alt fa-3x"></i>
            </div>
        </div>
        
        <h3 class="fw-bold text-success">Kembalikan Barang</h3>
        <p class="text-muted">Sistem mendeteksi Anda membawa:</p>
        
        <div class="bg-light p-3 rounded-3 mb-4">
            <h5 class="mb-1 fw-bold"><?= $barang['nama_barang'] ?></h5>
            <p class="small text-muted mb-0">Dipinjam pada: <?= date('d M Y, H:i', strtotime($trx['tgl'])) ?></p>
        </div>

        <form action="<?= base_url('client/proses_aksi') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="barang_id" value="<?= $barang['id'] ?>">
            <input type="hidden" name="action" value="kembali">
            
            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold mb-2 shadow">
                <i class="fas fa-arrow-left me-2"></i> KEMBALIKAN SEKARANG
            </button>
            <a href="<?= base_url('client') ?>" class="btn btn-link text-muted">Nanti Saja</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>