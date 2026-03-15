<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - SARPRAS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-color: #0f172a;
            --sidebar-hover: #1e293b;
            --active-color: #3b82f6;
            --bg-body: #f1f5f9;
            --transition-speed: 0.3s;
        }
        
        body { 
            background-color: var(--bg-body); 
            overflow-x: hidden;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        /* --- Sidebar Styling --- */
        .sidebar { 
            height: 100vh; 
            background: var(--sidebar-color); 
            color: white; 
            position: fixed;
            width: 260px;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            left: 0;
            display: flex;
            flex-direction: column;
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            .sidebar { left: -260px; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0 !important; }
            .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040; transition: 0.3s; }
            .overlay.show { display: block; }
        }

        .sidebar .brand {
            padding: 25px 20px;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .nav-link { 
            color: #94a3b8; 
            padding: 12px 20px;
            margin: 4px 15px;
            border-radius: 12px;
            transition: 0.2s;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 14px;
        }

        .nav-link:hover { 
            background: var(--sidebar-hover); 
            color: white; 
        }

        .nav-link.active { 
            background: var(--active-color); 
            color: white; 
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .nav-link i { width: 30px; font-size: 18px; }

        /* --- Top Navbar --- */
        .main-content { 
            margin-left: 260px;
            transition: all var(--transition-speed);
            min-height: 100vh;
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 12px 24px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* --- Profile Dropdown Modern --- */
        .user-profile:hover .avatar-box {
            transform: scale(1.05);
        }

        .avatar-box {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            transition: 0.3s;
        }

        .dropdown-menu {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            min-width: 240px;
            padding: 8px;
            animation: dropdownFade 0.2s ease-out;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--active-color);
        }

        /* --- Global Helpers --- */
        .card-custom {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .btn-toggle {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f1f5f9;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay"></div>

<div class="d-flex">
    <aside class="sidebar shadow" id="sidebar">
        <div class="brand">
            <div class="bg-info rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="fas fa-tools text-white fa-xs"></i>
            </div>
            <span>SARPRAS</span>
        </div>
        
        <div class="mt-4 flex-grow-1">
            <ul class="nav flex-column">
                <li><a class="nav-link <?= (url_is('dashboard*')) ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
                <li><a class="nav-link <?= (url_is('transaksi*')) ? 'active' : '' ?>" href="<?= base_url('transaksi') ?>"><i class="fas fa-exchange-alt"></i> Transaksi</a></li>
                <li><a class="nav-link <?= (url_is('barang*')) ? 'active' : '' ?>" href="<?= base_url('barang') ?>"><i class="fas fa-boxes-stacked"></i> Data Barang</a></li>
                <li><a class="nav-link <?= (url_is('lokasi*')) ? 'active' : '' ?>" href="<?= base_url('lokasi') ?>"><i class="fas fa-map-marked-alt"></i> Lokasi</a></li>
                
                <li class="mt-4 mb-2"><small class="text-uppercase px-4 text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Pengaturan</small></li>
                <li><a class="nav-link <?= (url_is('users*')) ? 'active' : '' ?>" href="<?= base_url('users') ?>"><i class="fas fa-users-cog"></i> Kelola User</a></li>
            </ul>
        </div>

        <div class="p-3">
            <a class="nav-link text-danger bg-danger bg-opacity-10 mb-3" href="<?= base_url('logout') ?>" onclick="return confirm('Yakin ingin keluar?')">
                <i class="fas fa-power-off"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content w-100">
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn-toggle me-3 d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="d-none d-md-block">
                    <h6 class="m-0 fw-bold">Pusat Inventaris</h6>
                    <small class="text-muted">Kelola sarana & prasarana dengan mudah</small>
                </div>
            </div>
            
            <div class="dropdown">
                <div class="user-profile d-flex align-items-center gap-3" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <p class="mb-0 fw-bold small text-dark"><?= session()->get('username') ?: 'Administrator' ?></p>
                        <small class="text-success fw-semibold" style="font-size: 11px;">
                            <i class="fas fa-circle fa-xs me-1"></i> <?= session()->get('role') ?: 'Super Admin' ?>
                        </small>
                    </div>
                    
                    <div class="position-relative">
                        <div class="avatar-box d-flex align-items-center justify-content-center text-white shadow-sm">
                            <i class="fas fa-user-tie fa-lg"></i>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle"></span>
                    </div>
                </div>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" aria-labelledby="userMenu">
                    <li class="px-3 py-3 border-bottom mb-2 bg-light rounded-top-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
                                <i class="fas fa-id-badge fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold small"><?= session()->get('username') ?></h6>
                                <p class="mb-0 text-muted" style="font-size: 11px;"><?= session()->get('email') ?: 'admin@email.com' ?></p>
                            </div>
                        </div>
                    </li>
                    <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-user-circle me-2 text-muted"></i> Profil Saya</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('settings') ?>"><i class="fas fa-shield-alt me-2 text-muted"></i> Keamanan</a></li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li><a class="dropdown-item text-danger fw-semibold" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Keluar Aplikasi</a></li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-3 p-md-4">
            
            <?php if(session()->getFlashdata('msg')): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div><?= session()->getFlashdata('msg') ?></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Logic for Sidebar Toggle on Mobile
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    const handleSidebar = () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    };

    toggleBtn?.addEventListener('click', handleSidebar);
    overlay?.addEventListener('click', handleSidebar);

    // Auto-close alert after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
</script>

</body>
</html>