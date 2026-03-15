<?php
/**
 * LOGIKA DETEKSI AJAX
 */
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || isset($_GET['ajax'])) {
    if(!empty($history)): 
        foreach($history as $h): ?>
            <tr class="align-middle border-transparent border-bottom">
                <td class="px-4 py-3">
                    <div class="fw-bold text-emphasis mb-0"><?= esc($h['nama_barang']) ?></div>
                    <div class="text-muted small-text text-uppercase"><?= esc($h['kode_transaksi']) ?></div>
                </td>
                <td class="text-center">
                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border-0 px-3"><?= $h['qty'] ?></span>
                </td>
                <td>
                    <?php 
                    $status = strtolower($h['status']);
                    $badgeClass = match(true) {
                        $status == 'pending' => 'bg-warning-subtle text-warning',
                        in_array($status, ['approved', 'dipinjam', 'disetujui']) => 'bg-info-subtle text-info',
                        $status == 'ditolak' => 'bg-danger-subtle text-danger',
                        in_array($status, ['kembali', 'selesai']) => 'bg-success-subtle text-success',
                        default => 'bg-light text-dark'
                    };
                    $icon = match(true) {
                        $status == 'pending' => 'fa-spinner fa-spin',
                        in_array($status, ['approved', 'dipinjam']) => 'fa-book-reader',
                        $status == 'ditolak' => 'fa-times-circle',
                        default => 'fa-check-double'
                    };
                    ?>
                    <span class="badge badge-custom <?= $badgeClass ?> border-0 px-3">
                        <i class="fas <?= $icon ?> me-1"></i> <?= ucfirst($status) ?>
                    </span>
                </td>
                <td class="text-end px-4">
                    <div class="fw-medium text-emphasis small mb-0"><?= date('H:i', strtotime($h['tgl'])) ?></div>
                    <div class="text-muted small-text"><?= date('d M Y', strtotime($h['tgl'])) ?></div>
                </td>
            </tr>
        <?php endforeach;
    else: ?>
        <tr><td colspan="4" class="text-center py-5 text-muted small">Tidak ada riwayat aktivitas.</td></tr>
    <?php endif;
    exit;
}
?>

<?= $this->extend('layout/clients') ?> 
<?= $this->section('content') ?>

<script src="https://unpkg.com/html5-qrcode"></script>

<div class="container py-2">
    <div class="row align-items-center mb-5 animate-fade-in">
        <div class="col-md-8">
            <h6 class="text-primary fw-bold text-uppercase mb-1" style="letter-spacing: 2px;">Workspace</h6>
            <h2 class="fw-extrabold text-emphasis display-6 mb-1">Halo, <?= explode(' ', session()->get('nama'))[0] ?>! 👋</h2>
            <p class="text-muted mb-0">Manajemen sarana dan prasarana dalam satu genggaman.</p>
        </div>
        <div class="col-md-4 text-md-end d-none d-md-block">
            <div id="clock" class="fw-bold text-emphasis h4 mb-0"></div>
            <div class="text-muted small"><?= date('l, d F Y') ?></div>
        </div>
    </div>

    <div class="row g-3 mb-5 animate-fade-in" style="animation-delay: 0.1s;">
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 shadow-sm border-0 rounded-4 d-flex align-items-center flex-row">
                <div class="stat-icon bg-primary-subtle text-primary me-3">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <div class="small text-muted">Aset</div>
                    <div class="fw-bold text-emphasis"><?= count($history ?? []) ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 shadow-sm border-0 rounded-4 d-flex align-items-center flex-row">
                <div class="stat-icon bg-warning-subtle text-warning me-3">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <div class="small text-muted">Pending</div>
                    <div class="fw-bold text-emphasis" id="count-pending">...</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5 animate-fade-in" style="animation-delay: 0.2s;">
        <div class="col-6">
            <div class="card action-card border-0 shadow-lg text-white h-100 overflow-hidden cursor-pointer" 
                 onclick="startScanner()" 
                 style="background: linear-gradient(135deg, #0d6efd 0%, #00d2ff 100%); border-radius: 28px;">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="glass-circle mb-3 mx-auto">
                        <i class="fas fa-expand fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-1">PINJAM</h4>
                    <p class="small opacity-75 mb-0 d-none d-md-block">Scan kode QR pada aset</p>
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <a href="<?= base_url('client/minta') ?>" class="text-decoration-none h-100 d-block">
                <div class="card action-card border-0 shadow-lg text-white h-100 overflow-hidden" 
                     style="background: linear-gradient(135deg, #198754 0%, #a2d240 100%); border-radius: 28px;">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="glass-circle mb-3 mx-auto">
                            <i class="fas fa-shopping-basket fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-1">MINTA</h4>
                        <p class="small opacity-75 mb-0 d-none d-md-block">Permintaan ATK baru</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div id="scanner-area" class="mt-4 mb-5 animate-fade-in" style="display:none;">
        <div class="card border-0 shadow-lg overflow-hidden mx-auto bg-dark text-white" style="border-radius: 30px; max-width: 500px;">
            <div class="card-header border-0 d-flex justify-content-between align-items-center py-4 px-4 bg-transparent text-white">
                <span class="fw-bold text-uppercase small" style="letter-spacing: 1px;">
                    <i class="fas fa-circle text-danger blink me-2"></i> Scanner Aktif
                </span>
                <button type="button" class="btn-close btn-close-white" onclick="stopScanner()"></button>
            </div>
            <div id="reader" style="width: 100%; border: none; background: #000;"></div>
            <div class="p-3 bg-dark">
                <button class="btn btn-outline-danger w-100 py-3 rounded-pill fw-bold" onclick="stopScanner()">BATALKAN PEMINJAMAN</button>
            </div>
        </div>
    </div>

    <div class="mt-5 animate-fade-in" style="animation-delay: 0.3s;">
        <div class="d-flex justify-content-between align-items-end mb-4 px-2">
            <div>
                <h4 class="fw-bold text-emphasis m-0">Aktivitas Terbaru</h4>
                <p class="text-muted small mb-0">Status pengajuan real-time</p>
            </div>
            <div class="text-end d-flex align-items-center">
                <div class="pulse-green me-2"></div>
                <button onclick="updateRiwayat()" class="btn btn-link p-0 text-primary text-decoration-none">
                    <i id="sync-icon" class="fas fa-sync-alt me-1"></i> <span id="sync-text" class="small fw-bold d-none d-sm-inline">Update</span>
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-content-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase" style="letter-spacing: 1px;">
                            <th class="px-4 py-3 border-0 bg-secondary bg-opacity-10">Aset & Barang</th>
                            <th class="py-3 text-center border-0 bg-secondary bg-opacity-10">Jumlah</th>
                            <th class="py-3 border-0 bg-secondary bg-opacity-10">Status</th>
                            <th class="py-3 text-end px-4 border-0 bg-secondary bg-opacity-10">Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="riwayat-body">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Variabel Khusus Halaman Index */
    [data-bs-theme="light"] {
        --card-stat-bg: #ffffff;
        --content-card-bg: #ffffff;
        --text-emphasis: #111827;
    }

    [data-bs-theme="dark"] {
        --card-stat-bg: rgba(30, 41, 59, 0.7);
        --content-card-bg: rgba(30, 41, 59, 0.5);
        --text-emphasis: #f8fafc;
    }

    .text-emphasis { color: var(--text-emphasis) !important; }
    .fw-extrabold { font-weight: 800; }
    .small-text { font-size: 10px; letter-spacing: 0.5px; }
    .cursor-pointer { cursor: pointer; }

    /* Stat Cards */
    .stat-card { 
        background-color: var(--card-stat-bg) !important; 
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid var(--bs-border-color-translucent) !important;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    /* Content Card (Table Container) */
    .bg-content-card {
        background-color: var(--content-card-bg) !important;
        backdrop-filter: blur(10px);
        border: 1px solid var(--bs-border-color-translucent) !important;
    }

    /* Action Cards */
    .action-card { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: none; }
    .action-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35) !important; }
    .glass-circle { background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 20px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px); }

    /* Badge Customization */
    .badge-custom { padding: 8px 14px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
    
    /* Animations */
    .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* Status Pulse & Blink */
    .pulse-green { width: 10px; height: 10px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); animation: pulse 2s infinite; }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); } 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); } }
    .blink { animation: blinker 1s linear infinite; }
    @keyframes blinker { 50% { opacity: 0.3; } }
</style>

<script>
    // Fungsi Update Jam
    function updateClock() {
        const now = new Date();
        const clockElement = document.getElementById('clock');
        if(clockElement) {
            clockElement.innerText = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        }
    }
    setInterval(updateClock, 1000); updateClock();

    let html5QrScanner;
    const riwayatBody = document.getElementById('riwayat-body');

    // Fungsi AJAX Refresh Riwayat
    function updateRiwayat() {
        const syncIcon = document.getElementById('sync-icon');
        if(syncIcon) syncIcon.classList.add('fa-spin');
        
        fetch(window.location.href + (window.location.href.includes('?') ? '&' : '?') + 'ajax=1', {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.text())
        .then(html => {
            if (riwayatBody && riwayatBody.innerHTML.trim() !== html.trim()) {
                riwayatBody.innerHTML = html;
                const pendingCount = (riwayatBody.innerHTML.match(/Pending/gi) || []).length;
                const countElem = document.getElementById('count-pending');
                if(countElem) countElem.innerText = pendingCount;
            }
        })
        .finally(() => {
            if(syncIcon) setTimeout(() => syncIcon.classList.remove('fa-spin'), 500);
        });
    }

    updateRiwayat();
    setInterval(updateRiwayat, 10000);

    // Fungsi Scanner
    function startScanner() {
        const area = document.getElementById('scanner-area');
        area.style.display = 'block';
        area.scrollIntoView({ behavior: 'smooth' });

        if (html5QrScanner) { html5QrScanner.clear(); }
        html5QrScanner = new Html5QrcodeScanner("reader", { 
            fps: 20, 
            qrbox: {width: 280, height: 280}, 
            aspectRatio: 1.0 
        });
        html5QrScanner.render((decodedText) => {
            new Audio('https://www.soundjay.com/button/beep-07.mp3').play();
            html5QrScanner.clear().then(() => {
                window.location.href = "<?= base_url('client/scan_check') ?>?kode=" + encodeURIComponent(decodedText);
            });
        });
    }

    function stopScanner() {
        if (html5QrScanner) {
            html5QrScanner.clear().then(() => { 
                document.getElementById('scanner-area').style.display = 'none'; 
            });
        }
    }
</script>

<?= $this->endSection() ?>