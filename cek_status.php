<!DOCTYPE html>
<html>
<head>
    <title>Cek Status</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #2596be 0%, #1a7a9e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 20px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #2596be 0%, #1a7a9e 100%) !important;
            padding: 2rem;
            border: none;
        }

        .card-header h5 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #344767;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #2596be;
            box-shadow: 0 0 0 0.2rem rgba(37, 150, 190, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2596be 0%, #1a7a9e 100%);
            border: none;
            padding: 0.875rem;
            font-weight: 600;
            font-size: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 150, 190, 0.3);
            background: linear-gradient(135deg, #1a7a9e 0%, #2596be 100%);
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .parking-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .qr-box {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 1.5rem;
        }

        .qr-box img {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .qr-box h6 {
            color: #344767;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .qr-code-text {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #2596be;
            font-size: 1.1rem;
        }

        hr {
            margin: 1.5rem 0;
            opacity: 0.1;
        }
    </style>
    
</head>
<body>

    <div class="container">
    
        <div class="row justify-content-center">
        
            <div class="col-md-6 col-lg-5">
            
                <div class="card shadow-lg border-0">
                
                    <div class="card-header text-white text-center">
                        <div class="icon-wrapper">
                            <span class="parking-icon">
                                <img src="assets-img/logo.webp" alt="EZParking Logo">
                            </span>
                        </div>
                        <h5>Cek Status Member</h5>
                        <p class="mb-0 mt-2" style="font-size: 0.9rem; opacity: 0.9;">
                            Masukkan plat nomor kendaraan Anda
                        </p>
                    </div>
                    
                    <div class="card-body">
                    
                        <form method="POST">
                        
                            <div class="mb-4">
                                <label class="form-label">Plat Nomor</label>
                                <input type="text" 
                                       name="plat" 
                                       class="form-control" 
                                       placeholder="Contoh: B 1234 XYZ"
                                       required>
                            </div>
                            
                            <button type="submit"
                                    name="cek"
                                    class="btn btn-primary w-100 rounded-pill">
                                 Cek Status
                            </button>
                            <a href="login.php" class="btn btn-primary w-100 rounded-pill mt-3">Kembali</a>
                            
                        </form>
                        
                        <hr>
                        
                        <?php
                        include 'config/database.php';

                        if(isset($_POST['cek'])){
                            
                            $plat = $_POST['plat'];
                            
                            $q = mysqli_query($koneksi, "
                                SELECT * FROM tb_daftar_member
                                WHERE plat_nomor='$plat'
                            ");
                            
                            if(mysqli_num_rows($q) == 0){
                                
                                echo "<div class='alert alert-danger mt-3'>
                                        <strong> Belum Terdaftar</strong><br>
                                        <small>Plat nomor tidak ditemukan dalam sistem.</small>
                                      </div>";
                                
                            } else {
                                
                                $d = mysqli_fetch_assoc($q);
                                $status = $d['status'];
                                
                                if($status == 'approved'){
                                    
                                    echo "<div class='alert alert-success mt-3'>
                                            <strong> Status: Disetujui</strong><br>
                                            <small>Keanggotaan Anda telah disetujui.</small>
                                          </div>";
                                    
                                    // ambil kendaraan
                                    $k = mysqli_fetch_assoc(mysqli_query($koneksi, "
                                        SELECT * FROM tb_kendaraan
                                        WHERE plat_nomor='$plat'
                                    "));
                                    
                                    $kode = $k['kode_member'];
                                    ?>
                                    
                                    <div class="qr-box text-center">
                                        
                                        <h6>QR Code Member</h6>
                                        
                                        <img src="assets/qr-image.php?text=<?= urlencode($kode) ?>"
                                             width="200" 
                                             class="border p-2 bg-white"
                                             alt="QR Code">
                                        
                                        <p class="qr-code-text mt-3 mb-3"><?= $kode ?></p>
                                        
                                        <a href="download_qr.php?kode=<?= $kode ?>"
                                           class="btn btn-outline-primary btn-sm rounded-pill">
                                             Download QR Code
                                        </a>
                                        
                                    </div>
                                    
                                    <?php
                                    
                                } elseif($status == 'rejected'){
                                    
                                    echo "<div class='alert alert-warning mt-3'>
                                            <strong> Status: Ditolak</strong><br>
                                            <small>Mohon maaf, pendaftaran Anda ditolak.</small>
                                          </div>";
                                    
                                } else {
                                    
                                    echo "<div class='alert alert-info mt-3'>
                                            <strong> Status: Menunggu Persetujuan</strong><br>
                                            <small>Pendaftaran Anda sedang diproses.</small>
                                          </div>";
                                    
                                }
                                
                            }
                            
                        }
                        ?>
                        
                    </div>
                    
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-white">© 2025 EZParking. All rights reserved.</small>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>