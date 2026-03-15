<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Transaksi</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('transaksi/store') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Peminjam (User)</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Pilih User --</option>
                                <?php foreach ($list_user as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= esc($u['nama']) ?> (<?= esc($u['role']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Barang</label>
                            <select name="barang_id" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach ($list_barang as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= esc($b['nama_barang']) ?> - Stok: <?= $b['stok'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select name="jenis" class="form-select" required>
                                <option value="pinjam">Pinjam</option>
                                <option value="minta">Minta (Permanen)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="qty" class="form-control" value="1" min="1" required>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keperluan</label>
                            <textarea name="keperluan" class="form-control" rows="3" placeholder="Masukkan keperluan..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('transaksi') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

