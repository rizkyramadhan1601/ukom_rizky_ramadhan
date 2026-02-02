<?php
include 'config/database.php';

$id = $_GET['id'];

mysqli_query($koneksi,"
UPDATE tb_daftar_member
SET status='rejected'
WHERE id_daftar='$id'
");

header("Location: data_pendaftaran.php");
