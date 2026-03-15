<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row align-items-center mb-4 g-3">
    <div class="col-12 col-md-6 text-center text-md-start">
        <h4 class="fw-bold mb-1">Dashboard Overview</h4>
        <p class="text-muted small mb-0">Selamat datang kembali, kelola aset Anda hari ini.</p>
    </div>
    <div class="col-12 col-md-6 text-center text-md-end">
        <div class="p-2 bg-white rounded-3 shadow-sm d-inline-block border">
            <h5 class="fw-bold mb-0 text-primary" id="clock" style="font-variant-numeric: tabular-nums;">00:00:00</h5>
            <small class="text-muted"><?= date('l, d M Y') ?></small>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1 text-uppercase" style="font-size: 10px;">Total Barang</small>
                        <h4 class="fw-bold mb-0"><?= $totalBarang ?></h4>
                    </div>
                    <div class="icon-shape bg-primary text-white rounded-3 p-2">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1 text-uppercase" style="font-size: 10px;">Selesai</small>
                        <h4 class="fw-bold mb-0 text-success"><?= $selesai ?></h4>
                    </div>
                    <div class="icon-shape bg-success text-white rounded-3 p-2">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1 text-uppercase" style="font-size: 10px;">Pending</small>
                        <h4 class="fw-bold mb-0 text-warning"><?= $pending ?></h4>
                    </div>
                    <div class="icon-shape bg-warning text-white rounded-3 p-2">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1 text-uppercase" style="font-size: 10px;">Stok Habis</small>
                        <h4 class="fw-bold mb-0 text-danger"><?= $stokHabis ?></h4>
                    </div>
                    <div class="icon-shape bg-danger text-white rounded-3 p-2">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 p-2 bg-light border-dashed">
    <div class="d-flex align-items-center justify-content-center justify-content-md-between flex-wrap gap-3">
        <span class="fw-bold small"><i class="fas fa-bolt me-2 text-warning"></i>Aksi Cepat:</span>
        <div class="btn-group shadow-sm">
            <a href="<?= base_url('barang/create') ?>" class="btn btn-white btn-sm border fw-bold"><i class="fas fa-plus-circle me-1"></i> Barang</a>
            <a href="<?= base_url('transaksi') ?>" class="btn btn-white btn-sm border fw-bold"><i class="fas fa-exchange-alt me-1"></i> Transaksi</a>
            <a href="<?= base_url('users/create') ?>" class="btn btn-white btn-sm border fw-bold"><i class="fas fa-user-plus me-1"></i> User</a>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm p-3 p-md-4">
            <h6 class="fw-bold mb-4">Tren Transaksi Bulanan</h6>
            <div style="height: 300px;">
                <canvas id="chartBulanan"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm p-3 p-md-4">
            <h6 class="fw-bold mb-4">Kondisi Aset</h6>
            <div style="height: 300px;">
                <canvas id="chartKondisi"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-0 shadow-sm p-3 p-md-4">
            <h6 class="fw-bold mb-4">Penyebaran per Lokasi</h6>
            <div style="height: 350px;">
                <canvas id="chartLokasi"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Script jam real-time dan Chart.js (Sama seperti sebelumnya namun dioptimasi responsifnya)
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateClock, 1000);
    updateClock();

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    };

    // Chart Transaksi
    new Chart(document.getElementById('chartBulanan'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Aktivitas',
                data: <?= json_encode($chartBulanan) ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: commonOptions
    });

    // Chart Kondisi
    new Chart(document.getElementById('chartKondisi'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($kondisiChart, 'label')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($kondisiChart, 'total')) ?>,
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
            }]
        },
        options: commonOptions
    });

    // Chart Lokasi
    new Chart(document.getElementById('chartLokasi'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($lokasiChart, 'label')) ?>,
            datasets: [{
                label: 'Unit',
                data: <?= json_encode(array_column($lokasiChart, 'total')) ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 5
            }]
        },
        options: {
            ...commonOptions,
            scales: { y: { beginAtZero: true, grid: { display: false } } }
        }
    });
</script>

<?= $this->endSection() ?>