<!DOCTYPE html>
<html>
<head>
    <title>Join Member</title>
    
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

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
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
                        <h5>Join Member EZParking</h5>
                        <p class="mb-0 mt-2" style="font-size: 0.9rem; opacity: 0.9;">
                            Daftarkan kendaraan Anda sekarang
                        </p>
                    </div>
                    
                    <div class="card-body">
                    
                        <form action="proses_join.php" method="POST">
                        
                            <div class="mb-3">
                                <label class="form-label">Plat Nomor</label>
                                <input type="text" 
                                       name="plat_nomor" 
                                       class="form-control" 
                                       placeholder="Contoh: B 1234 XYZ" 
                                       required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" 
                                        class="form-select" 
                                        required>
                                    <option value="">-- Pilih Jenis Kendaraan --</option>
                                    <option value="motor"> Motor</option>
                                    <option value="mobil"> Mobil</option>
                                    <option value="lainnya"> Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Warna</label>
                                <input type="text" 
                                       name="warna" 
                                       class="form-control" 
                                       placeholder="Contoh: Hitam, Putih, Merah">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Nama Pemilik</label>
                                <input type="text" 
                                       name="pemilik" 
                                       class="form-control" 
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            
                            <button type="submit" 
                                    name="daftar"
                                    class="btn btn-primary w-100 rounded-pill">
                                 Daftar Sekarang
                            </button>
                            <a href="login.php" class="btn btn-primary w-100 rounded-pill mt-3">Kembali</a>
                            
                        </form>
                        
                    </div>
                    
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-white">© 2026 EZParking. All rights reserved.</small>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>