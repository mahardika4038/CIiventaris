<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARPRAS Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #00d2ff 100%);
        }

        [data-bs-theme="light"] {
            --bg-gradient: radial-gradient(circle at top right, rgba(13, 110, 253, 0.08), transparent),
                           radial-gradient(circle at bottom left, rgba(25, 135, 84, 0.08), transparent);
            --nav-bg: rgba(255, 255, 255, 0.8);
        }

        [data-bs-theme="dark"] {
            --bg-gradient: radial-gradient(circle at top right, rgba(13, 110, 253, 0.15), #0f172a),
                           radial-gradient(circle at bottom left, rgba(25, 135, 84, 0.1), #0f172a);
            --nav-bg: rgba(15, 23, 42, 0.8);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-gradient);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--bs-body-color);
            transition: background 0.5s ease;
        }

        /* Glassmorphism Navbar Premium */
        .navbar-custom { 
            background: var(--nav-bg);
            backdrop-filter: blur(15px) saturate(180%);
            -webkit-backdrop-filter: blur(15px) saturate(180%);
            border-bottom: 1px solid var(--bs-border-color-translucent);
            position: sticky; 
            top: 0; 
            z-index: 1020;
            padding: 0.75rem 0;
        }

        .navbar-brand {
            letter-spacing: -0.5px;
        }

        /* Theme Toggle Button */
        .btn-theme {
            width: 42px; 
            height: 42px; 
            border-radius: 14px;
            display: flex; 
            align-items: center; 
            justify-content: center;
            cursor: pointer; 
            border: 1px solid var(--bs-border-color-translucent);
            background: var(--bs-body-bg);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transition: var(--transition);
        }
        .btn-theme:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            border-color: var(--bs-primary);
        }

        /* User Profile Dropdown */
        .user-nav-pill {
            background: var(--bs-secondary-bg);
            padding: 5px 12px 5px 6px;
            border-radius: 50px;
            border: 1px solid var(--bs-border-color-translucent);
            transition: var(--transition);
        }
        .user-nav-pill:hover {
            background: var(--bs-tertiary-bg);
            border-color: var(--bs-primary-border-subtle);
        }

        .dropdown-menu {
            border: 1px solid var(--bs-border-color-translucent);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            margin-top: 15px !important;
            padding: 8px;
        }

        .dropdown-item {
            padding: 10px 16px;
            font-weight: 500;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary-text-emphasis);
            border-radius: 10px;
        }

        /* Animation Utility */
        .animate-in {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-custom mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-3" href="<?= base_url('client') ?>">
            <div class="shadow-sm rounded-3" style="width: 36px; height: 36px; display: grid; place-items: center; background: var(--primary-gradient);">
                <i class="fas fa-layer-group text-white fa-sm"></i>
            </div>
            <span class="text-emphasis">SARPRAS<span class="text-primary fw-extrabold">CORE</span></span>
        </a>
        
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <div class="btn-theme" onclick="toggleTheme()" title="Ganti Tema">
                <i class="fas fa-sun text-warning" id="themeIcon"></i>
            </div>

            <div class="dropdown">
                <div class="d-flex align-items-center gap-2 user-nav-pill cursor-pointer" data-bs-toggle="dropdown" role="button">
                    <img src="https://ui-avatars.com/api/?name=<?= session()->get('nama') ?>&background=0D6EFD&color=fff&bold=true" 
                         class="rounded-circle shadow-sm" width="32" height="32">
                    <div class="d-none d-sm-block">
                        <i class="fas fa-chevron-down fa-xs text-muted"></i>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end rounded-4 border-0 shadow-lg">
                    <li class="px-3 py-2 mb-2 border-bottom">
                        <div class="small text-muted">Masuk sebagai:</div>
                        <div class="fw-bold text-primary text-truncate" style="max-width: 150px;"><?= session()->get('nama') ?></div>
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-id-card-alt me-2 opacity-50"></i> Profil Saya</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2 opacity-50"></i> Pengaturan</a></li>
                    <li class="mt-2 pt-2 border-top">
                        <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="container animate-in">
    <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleTheme() {
        const html = document.documentElement;
        const icon = document.getElementById('themeIcon');
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        // Animasi transisi smooth
        html.style.transition = "all 0.5s ease";
        html.setAttribute('data-bs-theme', newTheme);
        
        // Update Icon
        updateIcon(newTheme);
        
        localStorage.setItem('theme', newTheme);
    }

    function updateIcon(theme) {
        const icon = document.getElementById('themeIcon');
        if (theme === 'dark') {
            icon.className = 'fas fa-moon text-info';
        } else {
            icon.className = 'fas fa-sun text-warning';
        }
    }

    // Init Theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    updateIcon(savedTheme);
</script>
</body>
</html>