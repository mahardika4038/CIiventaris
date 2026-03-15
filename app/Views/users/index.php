<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Manajemen Akun</h5>
            <p class="text-muted small mb-0">Kelola hak akses pengguna sistem SARPRAS</p>
        </div>
        <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Tambah User Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th>Username</th>
                    <th>Hak Akses / Role</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($rows)): ?>
                    <?php $no = 1; foreach($rows as $r): ?>
                    <tr>
                        <td class="text-center text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="fw-semibold"><?= esc($r['username']) ?></span>
                            </div>
                        </td>
                        <td>
                            <?php if($r['role'] == 'admin'): ?>
                                <span class="badge bg-primary rounded-pill px-3">Administrator</span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill px-3">Petugas / User</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3">Aktif</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="<?= base_url('users/edit/'.$r['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if($r['username'] !== session()->get('username')): ?>
                                    <a href="<?= base_url('users/delete/'.$r['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')"
                                       title="Hapus User">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Tidak bisa hapus diri sendiri">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada data user tambahan.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>