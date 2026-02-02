<?php

$kode = $_GET['kode'];

$url = "http://localhost/ukom_rizky/assets/qr-image.php?text=".$kode;

$img = file_get_contents($url);

header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="'.$kode.'.png"');

echo $img;
