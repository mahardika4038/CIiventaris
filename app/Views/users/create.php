<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="fas fa-<?= isset($row) ? 'edit' : 'user-plus' ?> me-2"></i>
                    <?= isset($row) ? 'Edit User' : 'Tambah User Baru' ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= base_url(isset($row) ? 'users/update/' . $row['id'] : 'users/store') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap..." 
                               value="<?= isset($row) ? esc($row['nama']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username untuk login..." 
                               value="<?= isset($row) ? esc($row['username']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password <?= isset($row) ? '(Kosongkan jika tidak ingin mengubah)' : '' ?></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter..." 
                               <?= !isset($row) ? 'required' : '' ?>>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Hak Akses (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Hak Akses --</option>
                            <option value="superadmin" <?= isset($row) && $row['role'] === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                            <option value="admin" <?= isset($row) && $row['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="client" <?= isset($row) && $row['role'] === 'client' ? 'selected' : '' ?>>Client</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('users') ?>" class="text-decoration-none text-muted">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i><?= isset($row) ? 'Update' : 'Simpan' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

