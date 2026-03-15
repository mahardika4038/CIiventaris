<?= $this->extend('layout/clients') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="card shadow border-0" style="border-radius: 15px;">
        <div class="card-body text-center">
            <h4 class="fw-bold text-primary">Konfirmasi Pinjam</h4>
            <hr>
            <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" width="100" class="mb-3">
            
            <h5><?= $barang['nama_barang'] ?></h5>
            <p class="text-muted">Kode: <?= $barang['kode_barang'] ?> | Lokasi: <?= $barang['lokasi_id'] ?></p>

            <form action="<?= base_url('client/proses_pinjam') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_barang" value="<?= $barang['id'] ?>">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success p-3 fw-bold">KLIK UNTUK PINJAM SEKARANG</button>
                    <a href="<?= base_url('client') ?>" class="btn btn-light text-muted">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>