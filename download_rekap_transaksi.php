<?php
// PENTING: Jangan ada spasi atau karakter apapun sebelum <?php
// Jangan ada echo atau output apapun sebelum $pdf->Output()

session_start();
require_once 'config/app.php';

// Cek autentikasi
if (!isset($_SESSION['login']) || !in_array($_SESSION['role'], ['owner', 'petugas'])) {
    header('Location: login.php');
    exit;
}

// Validasi parameter
if (!isset($_GET['tanggal_awal']) || !isset($_GET['tanggal_akhir'])) {
    exit('<h3>Error: Parameter tanggal tidak lengkap!</h3>'); // Gunakan exit, bukan die
}

$tanggal_awal = $_GET['tanggal_awal'];
$tanggal_akhir = $_GET['tanggal_akhir'];

// Validasi format tanggal
if (!strtotime($tanggal_awal) || !strtotime($tanggal_akhir)) {
    exit('<h3>Error: Format tanggal tidak valid!</h3>');
}

// Ambil data
$transaksis = get_rekap_transaksi($tanggal_awal, $tanggal_akhir, 999999, 0);

// Cek apakah ada data
if (empty($transaksis)) {
    exit('<h3>Tidak ada data transaksi pada periode yang dipilih.</h3>');
}

// Hitung statistik
$total_pendapatan = 0;
$total_motor = 0;
$total_mobil = 0;
$count_motor = 0;
$count_mobil = 0;

foreach ($transaksis as $trx) {
    $total_pendapatan += $trx['biaya_total'];
    
    if (strtolower($trx['jenis_kendaraan']) == 'motor') {
        $total_motor += $trx['biaya_total'];
        $count_motor++;
    } else {
        $total_mobil += $trx['biaya_total'];
        $count_mobil++;
    }
}

// Include FPDF
require_once('library/fpdf/fpdf.php');

// Class PDF Custom
class PDF extends FPDF
{
    private $tanggal_awal;
    private $tanggal_akhir;
    
    function __construct($ta, $tk) {
        parent::__construct('L', 'mm', 'A4');
        $this->tanggal_awal = $ta;
        $this->tanggal_akhir = $tk;
    }
    
    function Header()
    {
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 10, 'SISTEM PARKIR', 0, 1, 'C');
        
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 8, 'REKAP TRANSAKSI PARKIR', 0, 1, 'C');
        
        $this->SetFont('Arial', '', 11);
        $periode = date('d F Y', strtotime($this->tanggal_awal)) . ' s/d ' . 
                   date('d F Y', strtotime($this->tanggal_akhir));
        $this->Cell(0, 6, 'Periode: ' . $periode, 0, 1, 'C');
        $this->Ln(3);
    }
    
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' | Dicetak: ' . date('d-m-Y H:i'), 0, 0, 'C');
    }
}

// Buat PDF
$pdf = new PDF($tanggal_awal, $tanggal_akhir);
$pdf->AddPage();

// Ringkasan
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 7, 'RINGKASAN TRANSAKSI', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(70, 6, 'Total Transaksi: ' . count($transaksis) . ' transaksi', 1, 0, 'L');
$pdf->Cell(70, 6, 'Motor: ' . $count_motor . ' (Rp ' . number_format($total_motor, 0, ',', '.') . ')', 1, 0, 'L');
$pdf->Cell(70, 6, 'Mobil: ' . $count_mobil . ' (Rp ' . number_format($total_mobil, 0, ',', '.') . ')', 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(67, 6, 'Total: Rp ' . number_format($total_pendapatan, 0, ',', '.'), 1, 1, 'R');

$pdf->Ln(5);

// Header Tabel
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(52, 152, 219);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(28, 8, 'Kode Member', 1, 0, 'C', true);
$pdf->Cell(28, 8, 'Plat Nomor', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Jenis', 1, 0, 'C', true);
$pdf->Cell(38, 8, 'Waktu Masuk', 1, 0, 'C', true);
$pdf->Cell(38, 8, 'Waktu Keluar', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Durasi', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Biaya (Rp)', 1, 1, 'C', true);

$pdf->SetTextColor(0, 0, 0);

// Isi Tabel
$pdf->SetFont('Arial', '', 8);
$no = 1;
$pdf->SetFillColor(245, 245, 245);

foreach ($transaksis as $key => $trx) {
    $fill = ($key % 2 == 0);
    
    $pdf->Cell(10, 6, $no++, 1, 0, 'C', $fill);
    $pdf->Cell(28, 6, $trx['kode_member'], 1, 0, 'C', $fill);
    $pdf->Cell(28, 6, $trx['plat_nomor'], 1, 0, 'C', $fill);
    $pdf->Cell(20, 6, ucfirst($trx['jenis_kendaraan']), 1, 0, 'C', $fill);
    $pdf->Cell(38, 6, date('d-m-Y H:i', strtotime($trx['waktu_masuk'])), 1, 0, 'C', $fill);
    $pdf->Cell(38, 6, date('d-m-Y H:i', strtotime($trx['waktu_keluar'])), 1, 0, 'C', $fill);
    $pdf->Cell(20, 6, $trx['durasi_jam'] . ' jam', 1, 0, 'C', $fill);
    $pdf->Cell(35, 6, number_format($trx['biaya_total'], 0, ',', '.'), 1, 1, 'R', $fill);
}

// Total
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(52, 152, 219);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(182, 7, 'TOTAL PENDAPATAN', 1, 0, 'R', true);
$pdf->Cell(35, 7, number_format($total_pendapatan, 0, ',', '.'), 1, 1, 'R', true);

// Footer info - FIX: Cek apakah session nama_lengkap ada
$pdf->Ln(10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Cek apakah nama_lengkap ada di session
$nama_user = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 
             (isset($_SESSION['username']) ? $_SESSION['username'] : 'Sistem');

$pdf->Cell(0, 5, 'Dicetak oleh: ' . $nama_user, 0, 1, 'L');

// Output - PENTING: Ini harus di akhir, jangan ada output apapun setelah ini
$filename = 'Rekap_Transaksi_' . date('Ymd', strtotime($tanggal_awal)) . '_' . date('Ymd', strtotime($tanggal_akhir)) . '.pdf';
$pdf->Output('I', $filename);

// JANGAN ADA KODE APAPUN SETELAH $pdf->Output()
// JANGAN ADA ?> di akhir file