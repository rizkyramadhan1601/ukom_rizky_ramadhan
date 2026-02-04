<?php
session_start();
include 'config/database.php';

// Initialize variables
$showResult = false;
$memberData = null;
$status = '';
$kode = '';

if(isset($_POST['cek'])){
    $plat = $_POST['plat'];
    
    $q = mysqli_query($koneksi, "
        SELECT * FROM tb_daftar_member
        WHERE plat_nomor='$plat'
    ");
    
    if(mysqli_num_rows($q) == 0){
        $showResult = true;
        $status = 'not_found';
    } else {
        $d = mysqli_fetch_assoc($q);
        $status = $d['status'];
        $showResult = true;
        
        if($status == 'approved'){
            // Ambil data kendaraan
            $k = mysqli_query($koneksi, "
                SELECT k.*, d.* 
                FROM tb_kendaraan k
                JOIN tb_daftar_member d ON k.plat_nomor = d.plat_nomor
                WHERE k.plat_nomor='$plat'
            ");
            
            if(mysqli_num_rows($k) > 0){
                $memberData = mysqli_fetch_assoc($k);
                $kode = $memberData['kode_member'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status - EZParking</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            max-width: 520px;
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

        .form-control {
            border: 2px solid #e1f5fe;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafffe;
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

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1.25rem;
            margin-top: 1.5rem;
            animation: fadeInUp 0.5s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            color: #e65100;
            border-left: 4px solid #ff9800;
        }

        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #0277bd;
            border-left: 4px solid #2196f3;
        }

        .alert strong {
            display: block;
            font-size: 1.05rem;
            margin-bottom: 4px;
        }

        .alert small {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .qr-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 2rem;
            border-radius: 16px;
            margin-top: 1.5rem;
            text-align: center;
            border: 2px solid #e1f5fe;
            animation: fadeInUp 0.6s ease-out;
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.1);
        }

        .qr-box h6 {
            color: #0277bd;
            font-weight: 700;
            margin-bottom: 1.25rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .qr-box h6 i {
            color: #4fc3f7;
        }

        .qr-box img {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(3, 169, 244, 0.2);
            background: white;
            padding: 15px;
            transition: transform 0.3s ease;
        }

        .qr-box img:hover {
            transform: scale(1.05);
        }

        .qr-code-text {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #0288d1;
            font-size: 1.15rem;
            background: white;
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-block;
            margin: 1rem 0;
            border: 2px dashed #4fc3f7;
            letter-spacing: 1px;
        }

        .btn-outline-primary.btn-sm {
            padding: 0.65rem 1.5rem;
            font-size: 0.9rem;
            border-radius: 10px;
        }

        hr {
            margin: 1.75rem 0;
            opacity: 0.15;
            border-color: #4fc3f7;
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

        .status-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-bottom: 8px;
        }

        .status-icon.success {
            background: #4caf50;
            color: white;
        }

        .status-icon.danger {
            background: #f44336;
            color: white;
        }

        .status-icon.warning {
            background: #ff9800;
            color: white;
        }

        .status-icon.info {
            background: #2196f3;
            color: white;
        }

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

            .form-control {
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

            .qr-box {
                padding: 1.5rem 1rem;
            }

            .qr-box img {
                max-width: 260px;
            }

            .qr-code-text {
                font-size: 1rem;
                padding: 10px 16px;
            }

            .alert {
                padding: 1rem;
            }

            .alert strong {
                font-size: 0.95rem;
            }

            .alert small {
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

            .qr-box {
                padding: 1.25rem 0.9rem;
            }

            .qr-box img {
                max-width: 230px;
            }
        }

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
                        <h5>Cek Status Member</h5>
                        <p>Masukkan plat nomor kendaraan Anda</p>
                    </div>
                    
                    <div class="card-body">
                        <form method="POST" id="cekStatusForm">
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Plat Nomor
                                </label>
                                <input type="text" 
                                       name="plat" 
                                       class="form-control" 
                                       placeholder="Contoh: B 1234 XYZ"
                                       value="<?= isset($_POST['plat']) ? htmlspecialchars($_POST['plat']) : '' ?>"
                                       required>
                            </div>
                            
                            <button type="submit"
                                    name="cek"
                                    class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-search me-2"></i>
                                Cek Status
                            </button>
                            
                            <a href="login.php" 
                               class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                        </form>
                        
                        <?php if($showResult): ?>
                            <hr>
                            
                            <?php if($status == 'not_found'): ?>
                                <div class='alert alert-danger'>
                                    <div class="status-icon danger mb-2">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <strong><i class="fas fa-exclamation-circle me-1"></i> Belum Terdaftar</strong><br>
                                    <small>Plat nomor tidak ditemukan dalam sistem.</small>
                                </div>
                            
                            <?php elseif($status == 'approved' && $memberData): ?>
                                <div class='alert alert-success'>
                                    <div class="status-icon success mb-2">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <strong><i class="fas fa-check-circle me-1"></i> Status: Disetujui</strong><br>
                                    <small>Keanggotaan Anda telah disetujui.</small>
                                </div>
                                
                                <div class="qr-box">
                                    <h6>
                                        <i class="fas fa-qrcode"></i>
                                        QR Code Member
                                    </h6>
                                    
                                    <img src="assets/qr-image.php?text=<?= urlencode($kode) ?>&size=350"
                                         width="280" 
                                         alt="QR Code"
                                         id="qrCodeImage">
                                    
                                    <div class="qr-code-text"><?= $kode ?></div>
                                    
                                    <button onclick="downloadMemberCard()" 
                                            class="btn btn-outline-primary btn-sm"
                                            id="downloadBtn">
                                        <i class="fas fa-download me-2"></i>
                                        Download Kartu Member
                                    </button>
                                </div>
                                
                                <!-- Hidden member data for JavaScript -->
                                <script>
                                    const memberData = {
                                        kode: '<?= $kode ?>',
                                        platNomor: '<?= addslashes($memberData['plat_nomor']) ?>',
                                        pemilik: '<?= addslashes($memberData['pemilik']) ?>',
                                        jenisKendaraan: '<?= ucfirst(addslashes($memberData['jenis_kendaraan'])) ?>',
                                        warna: '<?= addslashes($memberData['warna']) ?>',
                                        tanggal: '<?= date('d M Y', strtotime($memberData['created_at'] ?? 'now')) ?>'
                                    };
                                </script>
                            
                            <?php elseif($status == 'rejected'): ?>
                                <div class='alert alert-warning'>
                                    <div class="status-icon warning mb-2">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <strong><i class="fas fa-times-circle me-1"></i> Status: Ditolak</strong><br>
                                    <small>Mohon maaf, pendaftaran Anda ditolak.</small>
                                </div>
                            
                            <?php else: ?>
                                <div class='alert alert-info'>
                                    <div class="status-icon info mb-2">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <strong><i class="fas fa-info-circle me-1"></i> Status: Menunggu Persetujuan</strong><br>
                                    <small>Pendaftaran Anda sedang diproses.</small>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="footer-text">
                    <small>© 2026 EZParking. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <script>
        // Add loading state on form submit
        document.getElementById('cekStatusForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mencari...';
        });

        // Auto-uppercase plat nomor input
        const platInput = document.querySelector('input[name="plat"]');
        if (platInput) {
            platInput.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }

        // Download Member Card Function
        function downloadMemberCard() {
            if (typeof memberData === 'undefined') {
                alert('Data member tidak ditemukan');
                return;
            }
            
            // Create temporary card element
            const cardContainer = document.createElement('div');
            cardContainer.id = 'tempMemberCard';
            cardContainer.style.position = 'fixed';
            cardContainer.style.left = '-9999px';
            cardContainer.style.top = '-9999px';
            cardContainer.style.width = '650px'; // Ukuran kartu ATM proporsional
            cardContainer.style.height = '410px';
            
            cardContainer.innerHTML = `
                <div style="background: linear-gradient(135deg, #e0f7ff 0%, #b3e5fc 100%); border-radius: 16px; padding: 20px; position: relative; overflow: hidden; width: 650px; height: 410px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; position: relative; height: 100%; display: flex; flex-direction: column;">
                        <!-- Header -->
                        <div style="background: linear-gradient(135deg, #4fc3f7 0%, #03a9f4 100%); padding: 12px 20px; border-radius: 10px; margin: -20px -20px 15px -20px; display: flex; align-items: center; gap: 10px; color: white;">
                            <div style="width: 35px; height: 35px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #03a9f4;">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700; font-family: 'Inter', sans-serif;">EZParking Member</h3>
                        </div>
                        
                        <!-- Main Content -->
                        <div style="display: flex; gap: 15px; flex: 1; align-items: center;">
                            <!-- QR Code - Bigger -->
                            <div style="flex-shrink: 0;">
                                <div style="background: #f8f9fa; padding: 12px; border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);">
                                    <img src="assets/qr-image.php?text=${encodeURIComponent(memberData.kode)}&size=400" 
                                         style="display: block; width: 200px; height: 200px; border-radius: 6px;" 
                                         alt="QR Code"
                                         crossorigin="anonymous">
                                </div>
                            </div>
                            
                            <!-- Info Section -->
                            <div style="flex: 1; display: flex; flex-direction: column; gap: 10px;">
                                <!-- Code Box -->
                                <div style="border: 2px dashed #4fc3f7; border-radius: 10px; padding: 12px 15px; background: rgba(79, 195, 247, 0.05); text-align: center;">
                                    <p style="font-size: 1.6rem; font-weight: 800; color: #0288d1; font-family: 'Courier New', monospace; letter-spacing: 2px; margin: 0;">
                                        ${memberData.kode}
                                    </p>
                                </div>
                                
                                <!-- Member Info Compact -->
                                <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 12px; border-radius: 10px; border: 1px solid #e1f5fe; font-size: 0.75rem;">
                                    <div style="margin-bottom: 8px;">
                                        <div style="color: #546e7a; font-weight: 600; margin-bottom: 2px; font-family: 'Inter', sans-serif; font-size: 0.7rem;">
                                            <i class="fas fa-id-card" style="color: #4fc3f7; margin-right: 4px; font-size: 0.7rem;"></i>
                                            Plat Nomor
                                        </div>
                                        <div style="color: #0277bd; font-weight: 700; font-family: 'Inter', sans-serif; font-size: 0.85rem;">
                                            ${memberData.platNomor}
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 8px;">
                                        <div style="color: #546e7a; font-weight: 600; margin-bottom: 2px; font-family: 'Inter', sans-serif; font-size: 0.7rem;">
                                            <i class="fas fa-user" style="color: #4fc3f7; margin-right: 4px; font-size: 0.7rem;"></i>
                                            Pemilik
                                        </div>
                                        <div style="color: #0277bd; font-weight: 700; font-family: 'Inter', sans-serif; font-size: 0.85rem;">
                                            ${memberData.pemilik}
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div>
                                            <div style="color: #546e7a; font-weight: 600; margin-bottom: 2px; font-family: 'Inter', sans-serif; font-size: 0.65rem;">
                                                <i class="fas fa-car" style="color: #4fc3f7; margin-right: 3px; font-size: 0.65rem;"></i>
                                                Jenis
                                            </div>
                                            <div style="color: #0277bd; font-weight: 700; font-family: 'Inter', sans-serif; font-size: 0.75rem;">
                                                ${memberData.jenisKendaraan}
                                            </div>
                                        </div>
                                        <div>
                                            <div style="color: #546e7a; font-weight: 600; margin-bottom: 2px; font-family: 'Inter', sans-serif; font-size: 0.65rem;">
                                                <i class="fas fa-palette" style="color: #4fc3f7; margin-right: 3px; font-size: 0.65rem;"></i>
                                                Warna
                                            </div>
                                            <div style="color: #0277bd; font-weight: 700; font-family: 'Inter', sans-serif; font-size: 0.75rem;">
                                                ${memberData.warna}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div style="text-align: center; padding-top: 10px; border-top: 1px solid #e1f5fe; margin-top: 10px;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <div style="width: 25px; height: 25px;">
                                    <img src="assets-img/logo.webp" alt="EZParking" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div style="color: #546e7a; font-size: 0.7rem; font-family: 'Inter', sans-serif;">
                                    <strong>EZParking Member</strong> · ${memberData.tanggal}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(cardContainer);
            
            // Show loading
            const downloadBtn = document.getElementById('downloadBtn');
            const originalContent = downloadBtn.innerHTML;
            downloadBtn.disabled = true;
            downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat kartu...';
            
            // Capture with html2canvas
            setTimeout(() => {
                html2canvas(cardContainer.firstElementChild, {
                    scale: 2,
                    backgroundColor: null,
                    logging: false,
                    useCORS: true,
                    allowTaint: true
                }).then(canvas => {
                    // Create download link
                    const link = document.createElement('a');
                    link.download = `EZParking_Member_${memberData.kode}.png`;
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                    
                    // Cleanup
                    document.body.removeChild(cardContainer);
                    downloadBtn.disabled = false;
                    downloadBtn.innerHTML = originalContent;
                }).catch(error => {
                    console.error('Error generating card:', error);
                    alert('Terjadi kesalahan saat membuat kartu. Silakan coba lagi.');
                    document.body.removeChild(cardContainer);
                    downloadBtn.disabled = false;
                    downloadBtn.innerHTML = originalContent;
                });
            }, 500); // Delay to ensure QR code image loads
        }
    </script>
</body>
</html>