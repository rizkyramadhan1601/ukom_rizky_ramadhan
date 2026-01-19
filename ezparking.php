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
              <div id="reader" style="width:280px;"></div>
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

<!-- LIBRARY SCANNER -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
function onScanSuccess(decodedText) {
    document.getElementById('kode_member').value = decodedText;

    // stop kamera biar tidak double scan
    html5QrcodeScanner.clear();

    // submit otomatis
    document.getElementById('formScan').submit();
}

function onScanFailure(error) {
    // scanner tetap berjalan
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: { width: 220, height: 220 }
    },
    false
);

html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>

<?php include 'layout/footer.php'; ?>
