<?php
session_start();
require_once 'config/database.php';

if (!isset($_POST['kode_member'])) {
    die('Akses tidak valid');
}

$kode_member = mysqli_real_escape_string($koneksi, $_POST['kode_member']);

// ===============================
//  AMBIL DATA KENDARAAN
// ===============================
$kendaraan = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM tb_kendaraan 
    WHERE kode_member = '$kode_member'
"));

if (!$kendaraan) {
    echo "<script>alert('Kendaraan tidak ditemukan');history.back();</script>";
    exit;
}

$id_kendaraan = $kendaraan['id_kendaraan'];
$id_user      = $_SESSION['id_user']; // petugas login

// ===============================
// CEK TRANSAKSI AKTIF
// ===============================
$cek = mysqli_query($koneksi, "
    SELECT * FROM tb_transaksi 
    WHERE id_kendaraan = '$id_kendaraan'
    AND status = 'masuk'
    AND waktu_keluar IS NULL
");


// ==================================================
// JIKA BELUM ADA → MASUK
if (mysqli_num_rows($cek) == 0) {

    mysqli_begin_transaction($koneksi);

    try {

        // ambil tarif
        $tarif = mysqli_fetch_assoc(mysqli_query($koneksi, "
            SELECT * FROM tb_tarif 
            WHERE jenis_kendaraan = '{$kendaraan['jenis_kendaraan']}'
        "));

        if (!$tarif) {
            throw new Exception('Tarif tidak ditemukan');
        }

        // cari area sesuai jenis kendaraan
        $jenis = strtolower($kendaraan['jenis_kendaraan']);

        $area = mysqli_fetch_assoc(mysqli_query($koneksi, "
            SELECT * FROM tb_area_parkir
            WHERE LOWER(nama_area) LIKE '%$jenis%'
            AND terisi < kapasitas
            ORDER BY id_area ASC
            LIMIT 1
        "));

        if (!$area) {
            throw new Exception('Area parkir penuh');
        }

        $id_area = $area['id_area'];

        // INSERT TRANSAKSI
        mysqli_query($koneksi, "
            INSERT INTO tb_transaksi (
                id_kendaraan,
                waktu_masuk,
                status,
                id_tarif,
                id_user,
                id_area
            ) VALUES (
                '$id_kendaraan',
                NOW(),
                'masuk',
                '{$tarif['id_tarif']}',
                '$id_user',
                '$id_area'
            )
        ");

        //  KURANGI SLOT AREA
        mysqli_query($koneksi, "
            UPDATE tb_area_parkir
            SET terisi = terisi + 1
            WHERE id_area = '$id_area'
        ");

        mysqli_commit($koneksi);

        echo "<script>
            alert('KENDARAAN MASUK - {$area['nama_area']}');
            window.location.href='ezparking.php';
        </script>";
        exit;

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo "<script>alert('{$e->getMessage()}');history.back();</script>";
        exit;
    }
}



// ===============================
//  KELUAR
// ===============================
$trx = mysqli_fetch_assoc($cek);

mysqli_begin_transaction($koneksi);

try {

    $durasi = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT CEIL(TIMESTAMPDIFF(SECOND, waktu_masuk, NOW()) / 3600) AS durasi_jam
        FROM tb_transaksi
        WHERE id_parkir = '{$trx['id_parkir']}'
    "));

    $durasi_jam = max(1, (int)$durasi['durasi_jam']);

    $tarif = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT * FROM tb_tarif 
        WHERE id_tarif = '{$trx['id_tarif']}'
    "));

    $total = $durasi_jam * $tarif['tarif_per_jam'];

    mysqli_query($koneksi, "
        UPDATE tb_transaksi SET
            waktu_keluar = NOW(),
            durasi_jam   = '$durasi_jam',
            biaya_total  = '$total',
            status       = 'keluar'
        WHERE id_parkir = '{$trx['id_parkir']}'
    ");

    //  TAMBAH SLOT
    mysqli_query($koneksi, "
        UPDATE tb_area_parkir
        SET terisi = terisi - 1
        WHERE id_area = '{$trx['id_area']}'
        AND terisi > 0
    ");

    mysqli_commit($koneksi);

    header("Location: struk.php?id={$trx['id_parkir']}");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    die('Gagal memproses kendaraan keluar');
}
