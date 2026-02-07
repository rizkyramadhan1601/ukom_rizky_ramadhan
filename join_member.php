<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Member - EZParking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="assets-img/logo.webp" rel="icon">
    <link href="assets-img/logo.webp" rel="apple-touch-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #e0f7ff 0%, #b3e5fc 50%, #81d4fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 20px 15px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Circles */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            z-index: 0;
            opacity: 0.1;
        }

        body::before {
            width: 300px;
            height: 300px;
            background: #0288d1;
            top: -100px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        body::after {
            width: 200px;
            height: 200px;
            background: #03a9f4;
            bottom: -50px;
            left: -50px;
            animation: float 6s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(20px, 20px) rotate(180deg); }
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 480px;
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(3, 169, 244, 0.2);
            overflow: hidden;
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

        .card-header {
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 50%, #03a9f4 100%);
            padding: 2.5rem 1.5rem;
            border: none;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 24px rgba(3, 169, 244, 0.3);
            position: relative;
            z-index: 1;
            animation: bounce 2s ease-in-out infinite;
            padding: 15px;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .parking-icon {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .parking-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .card-header h5 {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
            letter-spacing: -0.5px;
        }

        .card-header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.95rem;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 2rem 1.75rem;
        }

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

        .input-wrapper {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e1f5fe;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafffe;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4fc3f7;
            box-shadow: 0 0 0 4px rgba(79, 195, 247, 0.1);
            background: white;
            outline: none;
        }

        .form-control::placeholder {
            color: #b0bec5;
        }

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

        .btn-outline-primary {
            background: transparent;
            border: 2px solid #4fc3f7;
            color: #03a9f4;
            padding: 0.95rem 1.5rem;
            font-weight: 600;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: #4fc3f7;
            border-color: #4fc3f7;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(79, 195, 247, 0.3);
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            animation: fadeIn 1s ease-out 0.3s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .footer-text small {
            color: #0277bd;
            font-weight: 500;
            font-size: 0.85rem;
            background: rgba(255, 255, 255, 0.7);
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            body {
                padding: 15px 10px;
            }

            .card-header {
                padding: 2rem 1.25rem;
            }

            .card-header h5 {
                font-size: 1.5rem;
            }

            .card-header p {
                font-size: 0.88rem;
            }

            .icon-wrapper {
                width: 85px;
                height: 85px;
                padding: 12px;
            }

            .card-body {
                padding: 1.5rem 1.25rem;
            }

            .form-control,
            .form-select {
                padding: 0.75rem 0.9rem;
                font-size: 0.9rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.85rem 1.25rem;
                font-size: 0.95rem;
            }

            .form-label {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 390px) {
            .card-header h5 {
                font-size: 1.35rem;
            }

            .icon-wrapper {
                width: 75px;
                height: 75px;
                padding: 10px;
            }

            .card-body {
                padding: 1.25rem 1rem;
            }

            .input-wrapper {
                margin-bottom: 1rem;
            }
        }

        /* Enhanced focus states for accessibility */
        .form-control:focus,
        .form-select:focus,
        .btn:focus {
            outline: 2px solid #4fc3f7;
            outline-offset: 2px;
        }

        /* Loading animation for submit button */
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
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="icon-wrapper">
                            <span class="parking-icon">
                                <img src="assets-img/logo.webp" alt="EZParking Logo">
                            </span>
                        </div>
                        <h5>Join Member EZParking</h5>
                        <p>Daftarkan kendaraan Anda sekarang</p>
                    </div>

                    <div class="card-body">
                        <form action="proses_join.php" method="POST" id="joinForm">
                            <div class="input-wrapper">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Plat Nomor
                                </label>
                                <input type="text"
                                       name="plat_nomor"
                                       class="form-control"
                                       placeholder="Contoh: B 1234 XYZ"
                                       required>
                            </div>

                            <div class="input-wrapper">
                                <label class="form-label">
                                    <i class="fas fa-car"></i>
                                    Jenis Kendaraan
                                </label>
                                <select name="jenis_kendaraan"
                                        class="form-select"
                                        required>
                                    <option value="">-- Pilih Jenis Kendaraan --</option>
                                    <option value="motor"> Motor</option>
                                    <option value="mobil"> Mobil</option>
                                    <option value="lainnya"> Lainnya</option>
                                </select>
                            </div>

                            <div class="input-wrapper">
                                <label class="form-label">
                                    <i class="fas fa-palette"></i>
                                    Warna
                                </label>
                                <input type="text"
                                       name="warna"
                                       class="form-control"
                                       placeholder="Contoh: Hitam, Putih, Merah">
                            </div>

                            <div class="input-wrapper">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    Nama Pemilik
                                </label>
                                <input type="text"
                                       name="pemilik"
                                       class="form-control"
                                       placeholder="Masukkan nama lengkap">
                            </div>

                            <button type="submit"
                                    name="daftar"
                                    class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Sekarang
                            </button>

                            <a href="login.php"
                               class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                        </form>
                    </div>
                </div>

                <div class="footer-text">
                    <small>
                        © 2026 EZParking. All rights reserved.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Optional: Add loading state on form submit
        document.getElementById('joinForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        });

        // Input validation & formatting
        const platNomorInput = document.querySelector('input[name="plat_nomor"]');
        if (platNomorInput) {
            platNomorInput.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }
    </script>
</body>
</html>