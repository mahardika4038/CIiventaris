<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Transaksi | Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
        }
        body { background-color: #f8fafc; color: #1e293b; }
        
        .main-card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); background: #fff; }
        .table thead th { background-color: #f1f5f9; font-size: 0.75rem; font-weight: 700; color: #64748b; padding: 1rem; border: none; text-transform: uppercase; }
        .align-middle td { padding: 1.1rem 1rem; border-bottom: 1px solid #f1f5f9; }

        .tr-status-pending { border-left: 5px solid #0d6efd; }
        .tr-status-dipinjam { border-left: 5px solid #0dcaf0; }
        .tr-status-selesai { border-left: 5px solid #198754; }
        .tr-status-ditolak { border-left: 5px solid #dc3545; }
        .tr-status-dikembalikan { border-left: 5px solid #6c757d; }

        .avatar-circle { width: 40px; height: 40px; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-weight: 700; }
        .btn-action { border-radius: 10px; font-weight: 600; transition: all 0.2s; }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        
        .badge-subtle { padding: 0.5em 1em; border-radius: 50px; font-weight: 600; font-size: 0.7rem; }
    </style>
</head>
<body>

<div class="container-fluid py-5 px-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm mb-2 rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Dashboard Utama
            </a>
            <h3 class="fw-bold mb-0">Riwayat Transaksi</h3>
            <p class="text-muted small mb-0">Kelola pengajuan dari client - Setuju / Tolak</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light btn-action shadow-sm px-3" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="<?= base_url('transaksi/create') ?>" class="btn btn-primary btn-action px-4">
                <i class="fas fa-plus me-2"></i>Tambah Transaksi
            </a>
        </div>

    <div class="main-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pemohon</th>
                        <th>Barang</th>
                        <th>Tipe</th>
                        <th class="text-center">Qty</th>
                        <th>Status</th>
                        <th class="text-end">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($rows)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Data transaksi belum tersedia.</td></tr>
                    <?php else: ?>
                        <?php foreach($rows as $r): ?>
                            <?php $st = strtolower($r['status']); ?>
                            <tr class="tr-status-<?= $st ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3"><?= strtoupper(substr($r['nama_user'] ?? 'U', 0, 1)) ?></div>
                                        <div>
                                            <div class="fw-bold text-dark small"><?= esc($r['nama_user'] ?? 'User') ?></div>
                                            <code class="text-muted" style="font-size: 0.65rem;"><?= esc($r['kode_transaksi']) ?></code>
                                        </div>
                                </td>
                                <td>
                                    <div class="fw-bold small"><?= esc($r['nama_barang']) ?></div>
                                    <small class="text-muted" style="font-size: 0.7rem;">Keperluan: <?= esc($r['keperluan']) ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-subtle <?= $r['jenis'] == 'pinjam' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-success-subtle text-success-emphasis' ?>">
                                        <?= strtoupper($r['jenis']) ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold"><?= $r['qty'] ?></td>
                                <td>
                                    <?php if($st == 'pending'): ?>
                                        <span class="badge bg-primary">MENUNGGU</span>
                                    <?php elseif($st == 'dipinjam'): ?>
                                        <span class="badge bg-info">DIPINJAM</span>
                                    <?php elseif($st == 'selesai'): ?>
                                        <span class="badge bg-success">SELESAI</span>
                                    <?php elseif($st == 'ditolak'): ?>
                                        <span class="badge bg-danger">DITOLAK</span>
                                    <?php elseif($st == 'dikembalikan'): ?>
                                        <span class="badge bg-secondary">DIKEMBALIKAN</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= strtoupper($st) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group shadow-sm">
                                        <?php if ($st === 'pending'): ?>
                                            <a href="<?= base_url('transaksi/approve/' . $r['id']) ?>" class="btn btn-sm btn-success border-0 px-3" title="Setuju">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?= base_url('transaksi/reject/' . $r['id']) ?>" class="btn btn-sm btn-danger border-0 px-3" title="Tolak" onclick="return confirm('Tolak transaksi ini?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php elseif ($st === 'dipinjam'): ?>
                                            <a href="<?= base_url('transaksi/kembali/' . $r['id']) ?>" class="btn btn-sm btn-info border-0 px-3 text-white" title="Konfirmasi Kembali">
                                                <i class="fas fa-undo"></i> Kembali
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= base_url('transaksi/edit/' . $r['id']) ?>" class="btn btn-sm btn-light border-0 px-3" title="Edit Data">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>

                                        <a href="<?= base_url('transaksi/delete/' . $r['id']) ?>" class="btn btn-sm btn-light border-0 px-3" onclick="return confirm('Hapus transaksi ini?')" title="Hapus">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
