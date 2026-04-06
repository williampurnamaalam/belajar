<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HRIS System | Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{asset('adminlte/css/adminlte.css')}}" />

    <style>
        body.login-page {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
        }
        .login-box {
            width: 400px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        .login-logo b {
            color: #007bff;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }
        .input-group-text {
            background-color: transparent;
            border-left: none;
            color: #adb5bd;
        }
        .form-control {
            border-right: none;
            padding: 12px;
            border-radius: 8px 0 0 8px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }
        .form-control:focus + .input-group-text {
            border-color: #dee2e6;
        }
        .social-auth-links .btn {
            border-radius: 8px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body class="login-page">
    <div class="login-box text-center">
        <div class="login-logo mb-4">
            <a href="#" class="text-decoration-none">
                <i class="bi bi-person-badge-fill text-primary mr-2"></i>
                <b>HRS</b>SYSTEM
            </a>
        </div>
        
        <div class="card card-outline card-primary">
            <div class="card-body login-card-body p-4">
                <h5 class="text-dark font-weight-bold mb-1">Selamat Datang</h5>
                <p class="text-muted small mb-4">Silakan masuk ke akun Anda</p>
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-start py-2" role="alert">
                    <small><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" class="form-control shadow-none" placeholder="Email" name="email" required autofocus />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control shadow-none" placeholder="Password" name="password" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            Masuk Ke Sistem
                        </button>
                    </div>
                </form>
        <p class="mt-4 text-muted small">&copy; 2026 SDM SISTEM</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="{{asset('adminlte/js/adminlte.js')}}"></script>
</body>
</html>
