<?php
/**
 * FILE IMAGE MURNI (ANTI RUSAK)
 * JANGAN INCLUDE FILE LAIN SELAIN QRLIB
 */

// HENTIKAN SEMUA OUTPUT SEBELUMNYA
while (ob_get_level()) {
    ob_end_clean();
}

// MATIKAN ERROR OUTPUT
ini_set('display_errors', 0);
error_reporting(0);

// HEADER IMAGE WAJIB PALING AWAL
header('Content-Type: image/png');

// LOAD LIBRARY QR (PATH FIX)
require_once __DIR__ . '/phpqrcode/qrlib.php';

// AMBIL DATA
$text = isset($_GET['text']) ? $_GET['text'] : 'EMPTY';

// GENERATE QR
QRcode::png($text, false, QR_ECLEVEL_L, 6);

// STOP TOTAL
exit;
