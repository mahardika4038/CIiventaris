<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<style>
    /* Styling search & table tetap sama seperti sebelumnya */
    .search-container { position: relative; width: 300px; }
    .search-container input { width: 100%; padding: 10px 15px 10px 40px; border-radius: 12px; border: 1px solid #e2e8f0; outline: none; transition: 0.3s; }
    .search-container input:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .search-container i { position: absolute; left: 15px; top: 13px; color: #94a3b8; }

    .table thead th { background: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 15px; border-top: none; }
    .table tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

    /* Gaya Baru untuk Ikon QR */
    .btn-qr-preview {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        border: 1px solid #e2e8f0;
    }
    .btn-qr-preview:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
        transform: translateY(-2px);
    }

    .badge-status { padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; }
    .status-tersedia { background: #dcfce7; color: #166534; }
    .status-dipinjam { background: #fee2e2; color: #991b1b; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Inventaris Barang</h4>
        <p class="text-muted small mb-0">Kelola aset dan akses QR Code dengan satu klik.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= base_url('barang/cetakSemua') ?>" target="_blank" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold border-0" style="background: #1e293b;">
            <i class="fas fa-print me-1"></i> Cetak Semua QR
        </a>
        <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold border-0" style="background: #10b981;" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-excel me-1"></i> Import
        </button>
        <a href="<?= base_url('barang/create') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold border-0" style="background: #3b82f6;">
            <i class="fas fa-plus-circle me-1"></i> Tambah Barang
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari Kode, Nama, atau Lokasi...">
            </div>
            <div class="text-muted small fw-medium">
                Total: <span class="text-dark fw-bold"><?= count($rows) ?></span> Item
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 80px;">Scan</th>
                    <th>Info Barang</th>
                    <th>Lokasi</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody id="inventoryTable">
                <?php foreach ($rows as $r): ?>
                <tr class="item-row">
                    <td class="ps-4">
                        <div class="btn-qr-preview" 
                             onclick="showQR('<?= $r['kode_barang'] ?>', '<?= esc($r['nama_barang']) ?>', '<?= $r['id'] ?>')"
                             title="Lihat QR Code">
                            <i class="fas fa-qrcode fa-lg"></i>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark mb-0"><?= esc($r['nama_barang']) ?></div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-primary border" style="font-family: monospace; font-size: 10px;"><?= $r['kode_barang'] ?></span>
                            <small class="text-muted"><?= $r['kategori'] ?></small>
                        </div>
                    </td>
                    <td>
                        <div class="small fw-semibold"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= $r['nama_lokasi'] ?? '-' ?></div>
                    </td>
                    <td class="text-center">
                        <span class="badge-status <?= (strtolower($r['status'] ?? '') == 'dipinjam') ? 'status-dipinjam' : 'status-tersedia' ?> text-uppercase">
                            <?= $r['status'] ?? 'Tersedia' ?>
                        </span>
                    </td>
                    <td class="text-center fw-bold text-dark"><?= $r['stok'] ?></td>
                    <td class="text-center pe-4">
                        <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                            <a href="<?= base_url('barang/edit/'.$r['id']) ?>" class="btn btn-white btn-sm border" title="Edit"><i class="fas fa-edit text-primary"></i></a>
                            <a href="<?= base_url('barang/delete/'.$r['id']) ?>" class="btn btn-white btn-sm border" onclick="return confirm('Hapus barang ini?')" title="Hapus"><i class="fas fa-trash text-danger"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Label Barang</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="bg-light p-3 rounded-4 mb-3">
                    <img id="modalQRImage" src="" class="img-fluid rounded-3 shadow-sm" alt="QR Code">
                </div>
                
                <h5 class="fw-bold text-dark mb-1" id="modalBarangName">Nama Barang</h5>
                <code class="text-primary d-block mb-3" id="modalBarangKode">KODE-BARANG</code>
                
                <div class="d-grid gap-2">
                    <a id="btnCetakSatuan" href="#" target="_blank" class="btn btn-primary rounded-pill">
                        <i class="fas fa-print me-1"></i> Print Label
                    </a>
                    <button onclick="downloadQR()" class="btn btn-outline-dark rounded-pill">
                        <i class="fas fa-download me-1"></i> Simpan Gambar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk memanggil Modal QR
    function showQR(kode, nama, id) {
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${kode}`;
        document.getElementById('modalQRImage').src = qrUrl;
        document.getElementById('modalBarangName').innerText = nama;
        document.getElementById('modalBarangKode').innerText = kode;
        document.getElementById('btnCetakSatuan').href = `<?= base_url('barang/cetakSatuan/') ?>/${id}`;
        
        const myModal = new bootstrap.Modal(document.getElementById('qrModal'));
        myModal.show();
    }

    // Fungsi Download Gambar QR
    function downloadQR() {
        const qrImg = document.getElementById('modalQRImage').src;
        const kode = document.getElementById('modalBarangKode').innerText;
        const link = document.createElement('a');
        link.href = qrImg;
        link.download = `QR_${kode}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.item-row');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
</script>

<?= $this->endSection() ?>