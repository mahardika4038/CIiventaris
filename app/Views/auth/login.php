<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sistem Sarpras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            background-color: #0d6efd;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .btn-login {
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .contact-admin {
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-card bg-white">
    <div class="login-header">
        <i class="fas fa-tools fa-3x mb-3"></i>
        <h4 class="fw-bold mb-0">SARPRAS LOGIN</h4>
        <small class="opacity-75">Manajemen Inventaris & Aset</small>
    </div>

    <div class="card-body p-4">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 small mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label fw-semibold small">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input name="username" class="form-control bg-light border-start-0" placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm">
                <i class="fas fa-sign-in-alt me-2"></i> MASUK KE SISTEM
            </button>
        </form>

        <hr class="my-4">

        <div class="contact-admin">
            <p class="mb-1">Belum punya akun?</p>
            <a href="https://wa.me/628123456789?text=Halo%20Admin,%20saya%20ingin%20membuat%20akun%20Sarpras" 
               target="_blank" class="text-decoration-none fw-bold text-success">
                <i class="fab fa-whatsapp me-1"></i> Hubungi Admin untuk Daftar
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>