
<?php
session_start();
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    die('ID transaksi tidak valid');
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT t.*, k.kode_member, k.plat_nomor, k.jenis_kendaraan, k.pemilik
    FROM tb_transaksi t
    JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
    WHERE t.id_parkir = '$id'
"));
?>

<!DOCTYPE html>
<html>
<head>
  <title>Struk Parkir</title>
<style>
    .back-area {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 60px;

    opacity: 0; 
    cursor: pointer;
    z-index: 9999;
    }

  body {
    font-family: monospace;
    background: #f5f5f5;
  }

  .struk {
    width: 300px;
    margin: 30px auto;
    background: #fff;
    padding: 15px;
    border: 1px dashed #000;
  }

  h3 {
    text-align: center;
    margin-bottom: 10px;
  }

  hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 10px 0;
  }

  .text-center {
    text-align: center;
  }
  @media print {
    body {
      margin: 0;
      background: #fff;
    }

    @page {
      size: auto;
      margin: 0;
    }
  }
</style>

</head>
<body onload="window.print()">

<div class="struk">
  <h3>EZ PARKING</h3>
  <div class="text-center">STRUK PARKIR</div>

  <hr>

  <p>Kode Member : <?= $data['kode_member'] ?></p>
  <p>Plat Nomor  : <?= $data['plat_nomor'] ?></p>
  <p>Jenis       : <?= ucfirst($data['jenis_kendaraan']) ?></p>
  <p>Pemilik     : <?= $data['pemilik'] ?></p>

  <hr>

  <p>Masuk  : <?= $data['waktu_masuk'] ?></p>
  <p>Keluar : <?= $data['waktu_keluar'] ?></p>
  <p>Jam Ke : <?= $data['durasi_jam'] ?></p>

  <hr>

  <p><strong>Total Bayar</strong></p>
  <h3>Rp <?= number_format($data['biaya_total'], 0, ',', '.') ?></h3>

  <hr>

  <div class="text-center">
    Terima Kasih<br>
    Simpan Struk Anda
  </div>
  <a href="ezparking.php" class="back-area">Kembali ke Dashboard</a>
</div>
</body>
</html>
