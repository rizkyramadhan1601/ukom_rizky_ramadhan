<?php
session_start();
include 'config/app.php';

// Pastikan fungsi log ada
if(!function_exists('log_aktivitas')){
    include 'function_log.php'; // kalau log ada di file lain
}

// Cek login
if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if(!$id){
    header("Location: data_pendaftaran.php");
    exit;
}


// Ambil data pendaftaran
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM tb_daftar_member
    WHERE id_daftar = '$id'
"));

if(!$data){
    header("Location: data_pendaftaran.php");
    exit;
}


$plat_nomor      = mysqli_real_escape_string($koneksi, $data['plat_nomor']);
$jenis_kendaraan = mysqli_real_escape_string($koneksi, $data['jenis_kendaraan']);
$warna           = mysqli_real_escape_string($koneksi, $data['warna']);
$pemilik         = mysqli_real_escape_string($koneksi, $data['pemilik']);

$id_user = $_SESSION['id_user'];


// ===============================
// CEK SUDAH ADA DI KENDARAAN?
// ===============================
$cek = mysqli_query($koneksi,"
    SELECT id_kendaraan
    FROM tb_kendaraan
    WHERE plat_nomor='$plat_nomor'
");

if(mysqli_num_rows($cek)>0){

    $_SESSION['error']="Plat sudah terdaftar";

    header("Location: data_pendaftaran.php");
    exit;
}


// ===============================
// INSERT KE tb_kendaraan
// ===============================
mysqli_query($koneksi, "
INSERT INTO tb_kendaraan
(plat_nomor, jenis_kendaraan, warna, pemilik, id_user)
VALUES
('$plat_nomor','$jenis_kendaraan','$warna','$pemilik','$id_user')
");


// Ambil ID kendaraan
$id_kendaraan = mysqli_insert_id($koneksi);


// ===============================
// GENERATE KODE MEMBER
// ===============================
$kode_member = 'MBR' . str_pad($id_kendaraan,5,'0',STR_PAD_LEFT);


// Update kode member
mysqli_query($koneksi,"
UPDATE tb_kendaraan
SET kode_member='$kode_member'
WHERE id_kendaraan='$id_kendaraan'
");


// ===============================
// UPDATE STATUS DAFTAR
// ===============================
mysqli_query($koneksi,"
UPDATE tb_daftar_member
SET status='approved'
WHERE id_daftar='$id'
");


// ===============================
// ✅ LOG AKTIVITAS REALTIME
// ===============================
log_aktivitas("Approve member $plat_nomor → dibuat kode $kode_member");


// ===============================
// REDIRECT
// ===============================
$_SESSION['success']="Pendaftaran berhasil disetujui";

header("Location: data_pendaftaran.php");
exit;
