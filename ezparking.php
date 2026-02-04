<?php include 'layout/header_tab.php'; ?>

<section class="about section py-5" style="margin-top:90px;">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="fw-bold">
        <i class="bi bi-qr-code-scan me-2"></i>Scan Masuk & Keluar
      </h2>
      <p class="text-muted mb-0">
        Scan QR Code kendaraan untuk proses parkir
      </p>
    </div>

    <!-- CARD SCAN -->
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg rounded-4">
          <div class="card-header bg-primary text-white text-center rounded-top-4">
            <strong>QR Code Scanner</strong>
          </div>
          <div class="card-body p-4 text-center">
            <div class="alert alert-info small mb-4">
              <i class="bi bi-info-circle me-1"></i>
              Arahkan QR Code ke kamera hingga terbaca otomatis
            </div>
            <div class="border rounded-3 p-2 bg-light d-inline-block">
              <div id="reader" style="width:100%; max-width:280px;"></div>
            </div>
            <form method="POST" action="proses_scan.php" id="formScan">
              <input type="hidden" name="kode_member" id="kode_member">
            </form>
            <p class="text-muted small mt-3 mb-0">
              Pastikan kamera aktif & QR terlihat jelas
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
@media (max-width: 576px) {
    #reader {
        max-width: 260px !important;
    }
}

@media (max-width: 390px) {
    #reader {
        max-width: 240px !important;
    }
}

#reader__dashboard_section {
    padding-top: 10px !important;
}

#reader__dashboard_section_csr button {
    margin: 5px !important;
}
</style>

<!-- LIBRARY SCANNER -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let isProcessing = false; // Mencegah double scan
let html5QrcodeScanner = null;

function onScanSuccess(decodedText) {
    // Cegah Double Scan
    if (isProcessing) {
        console.log('Already processing, ignoring...');
        return;
    }
    
    console.log('QR Code detected:', decodedText);
    isProcessing = true;
    
    // Set kode member
    document.getElementById('kode_member').value = decodedText;

    // Scan Delay Menghindari Double Scan
    html5QrcodeScanner.clear()
        .then(() => {
            console.log('Scanner stopped successfully');
            setTimeout(() => {
                document.getElementById('formScan').submit();
            }, 300);
        })
        .catch(err => {
            console.error('Error stopping scanner:', err);
            document.getElementById('formScan').submit();
        });
}

function onScanFailure(error) {
}

function initializeScanner() {
    
    const readerElement = document.getElementById('reader');
    const readerWidth = readerElement.offsetWidth || 280;
    
    // UKURAN BOX SCAN
    const qrboxSize = Math.min(Math.floor(readerWidth * 0.8), 250);
    
    console.log('Initializing scanner with QR box size:', qrboxSize);
    
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 15,                    // FPS UNTUK SCANNING
            qrbox: { 
                width: qrboxSize, 
                height: qrboxSize 
            },
            aspectRatio: 1.0,         
            disableFlip: false,         
            showTorchButtonIfSupported: true, 
            rememberLastUsedCamera: true,    
        },
        false 
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}


document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing scanner...');
    
    
    setTimeout(() => {
        initializeScanner();
    }, 100);
});


let resizeTimeout;
window.addEventListener('resize', function() {
    if (isProcessing) {
        return; 
    }
    
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        console.log('Window resized, reinitializing scanner...');
        
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear()
                .then(() => {
                    initializeScanner();
                })
                .catch(err => {
                    console.error('Error during resize reinitialization:', err);
                    
                    initializeScanner();
                });
        }
    }, 500); 
});


document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        console.log('Page hidden, pausing scanner...');
        if (html5QrcodeScanner && !isProcessing) {
            html5QrcodeScanner.pause();
        }
    } else {
        console.log('Page visible, resuming scanner...');
        if (html5QrcodeScanner && !isProcessing) {
            html5QrcodeScanner.resume();
        }
    }
});
</script>

<?php include 'layout/footer.php'; ?>