<?php
session_start();
include 'config/app.php';
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>EZParking Login</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets-img/logo.webp" rel="icon">
    <link href="assets-img/logo.webp" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 20px;
        }

        /* Background blur effect */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('assets-img/bg_login.webp') center / cover no-repeat;
            filter: blur(5px);
            transform: scale(1.1);
            z-index: 1;
        }

        /* Animated overlay circles */
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(79, 195, 247, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            z-index: 1;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-30px, 30px); }
        }

        /* Login card dengan glassmorphism */
        .login-card {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(3, 169, 244, 0.25);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 440px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo section */
        .logo-wrapper {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(3, 169, 244, 0.3);
            animation: bounce 2s ease-in-out infinite;
            padding: 15px;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Header */
        h4 {
            color: #0277bd;
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #546e7a;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Alert styling */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            animation: fadeInDown 0.5s ease-out;
            border-left: 4px solid #f44336;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #c62828;
        }

        /* Form labels */
        .form-label {
            font-weight: 600;
            color: #0277bd;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            font-size: 0.85rem;
            color: #4fc3f7;
        }

        /* Form controls */
        .form-control {
            border: 2px solid #e1f5fe;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(250, 255, 254, 0.8);
        }

        .form-control:focus {
            border-color: #4fc3f7;
            box-shadow: 0 0 0 4px rgba(79, 195, 247, 0.1);
            background: white;
            outline: none;
        }

        .form-control::placeholder {
            color: #b0bec5;
        }

        .mb-3 {
            margin-bottom: 1.25rem !important;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #4fc3f7 0%, #03a9f4 100%);
            border: none;
            padding: 0.95rem 1.5rem;
            font-weight: 600;
            border-radius: 12px;
            font-size: 1rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(3, 169, 244, 0.3);
            position: relative;
            overflow: hidden;
            color: white;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(3, 169, 244, 0.4);
            background: linear-gradient(135deg, #29b6f6 0%, #0288d1 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary i {
            margin-right: 6px;
        }

        /* Footer text */
        .footer-text {
            color: #546e7a;
            font-size: 0.8rem;
            text-align: center;
            margin-top: 1.5rem;
            margin-bottom: 0;
            padding-top: 1rem;
            border-top: 1px solid rgba(79, 195, 247, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }

            .login-card {
                padding: 2rem 1.5rem;
                max-width: 100%;
            }

            h4 {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 0.85rem;
            }

            .logo-wrapper {
                width: 85px;
                height: 85px;
                padding: 12px;
            }

            .form-control {
                padding: 0.75rem 0.9rem;
                font-size: 0.9rem;
            }

            .btn-primary {
                padding: 0.85rem 1.25rem;
                font-size: 0.95rem;
            }

            .form-label {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 390px) {
            .login-card {
                padding: 1.75rem 1.25rem;
            }

            h4 {
                font-size: 1.35rem;
            }

            .logo-wrapper {
                width: 75px;
                height: 75px;
                padding: 10px;
            }
        }

        /* Loading animation */
        .btn-primary.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-primary.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            right: 20px;
            margin-top: -8px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        /* Password toggle */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #4fc3f7;
            cursor: pointer;
            padding: 5px;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #0288d1;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo-wrapper">
            <img src="assets-img/logo.webp" alt="EZParking Logo">
        </div>

        <h4 class="text-center">LOGIN EZParking</h4>
        <p class="text-center subtitle">Masukkan kredensial Anda untuk melanjutkan</p>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i>
                    Username
                </label>
                <input type="text" 
                       name="username" 
                       id="username" 
                       class="form-control" 
                       placeholder="Masukkan username"
                       required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <div class="password-wrapper">
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control" 
                           placeholder="Masukkan password"
                           required>
                    <button type="button" 
                            class="password-toggle" 
                            onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" 
                    name="login" 
                    class="btn btn-primary w-100 mb-3">
                <i class="fas fa-sign-in-alt"></i>
                Masuk
            </button>

            <div class="row g-2">
                <div class="col-6">
                    <a href="join_member.php" class="btn btn-primary w-100">
                        <i class="fas fa-user-plus"></i>Join Member
                    </a>
                </div>
                <div class="col-6">
                    <a href="cek_status.php" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                        Cek Status
                    </a>
                </div>
            </div>
        </form>

        <p class="footer-text">
            © <?= date('Y') ?> M RIZKY RAMADHAN - All rights reserved
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add loading state on form submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        });

        // Prevent double submission
        let isSubmitting = false;
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
        });
    </script>
</body>
</html>