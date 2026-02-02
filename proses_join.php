<?php
include 'config/database.php';

if(isset($_POST['daftar'])){

$plat   = $_POST['plat_nomor'];
$jenis  = $_POST['jenis_kendaraan'];
$warna  = $_POST['warna'];
$pemilik= $_POST['pemilik'];

// Cek di pendaftaran
$cek = mysqli_query($koneksi,"
SELECT * FROM tb_daftar_member
WHERE plat_nomor='$plat'
");

if(mysqli_num_rows($cek)>0){
    echo "<script>alert('Plat sudah pernah daftar');location='join_member.php';</script>";
    exit;
}

// Cek di kendaraan
$cek2 = mysqli_query($koneksi,"
SELECT * FROM tb_kendaraan
WHERE plat_nomor='$plat'
");

if(mysqli_num_rows($cek2)>0){
    echo "<script>alert('Plat sudah jadi member');location='join_member.php';</script>";
    exit;
}

// Simpan
mysqli_query($koneksi,"
INSERT INTO tb_daftar_member
(plat_nomor,jenis_kendaraan,warna,pemilik)
VALUES
('$plat','$jenis','$warna','$pemilik')
");

echo "<script>
alert('Pendaftaran berhasil. Tunggu persetujuan.');
location='join_member.php';
</script>";

}
