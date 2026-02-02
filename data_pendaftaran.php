<?php
include 'config/database.php';
include 'layout/header_tab.php';

// Ambil data pendaftaran
$q = mysqli_query($koneksi,"
    SELECT * FROM tb_daftar_member
    ORDER BY created_at DESC
");

$daftars = [];
while($row = mysqli_fetch_assoc($q)){
    $daftars[] = $row;
}
?>

<section class="about section py-5" style="margin-top:90px;">
<div class="container">

<!-- JUDUL -->
<div class="text-center mb-4">

  <h2 class="fw-bold">
    <i class="bi bi-person-check me-2"></i>
    Persetujuan Member
  </h2>

  <p class="text-muted mb-0">
    Kelola pendaftaran member kendaraan EZParking
  </p>

</div>


<!-- CARD UTAMA -->
<div class="row justify-content-center">

<div class="col-lg-11">

<div class="card border-0 shadow-lg rounded-4">

<!-- HEADER CARD -->
<div class="card-header bg-primary text-white text-center rounded-top-4">
  <strong>Data Pengajuan Member</strong>
</div>

<div class="card-body p-4 table-responsive">


<table class="table align-middle mb-0">

<thead class="table-light text-center">
<tr>
    <th>No</th>
    <th>Plat</th>
    <th>Jenis</th>
    <th>Warna</th>
    <th>Pemilik</th>
    <th>Status</th>
    <th>Tindakan</th>
</tr>
</thead>

<tbody>

<?php if(!empty($daftars)) : ?>

<?php $no=1; ?>
<?php foreach($daftars as $d): ?>

<tr class="text-center">

<td><?= $no++ ?></td>

<td>
<span class="badge bg-dark bg-opacity-10 text-dark">
<?= $d['plat_nomor'] ?>
</span>
</td>

<td>
<span class="badge bg-primary bg-opacity-10 text-primary">
<?= ucfirst($d['jenis_kendaraan']) ?>
</span>
</td>

<td><?= $d['warna'] ?: '-' ?></td>

<td><?= $d['pemilik'] ?: '-' ?></td>

<td>

<?php if($d['status']=='pending'): ?>

<span class="badge bg-warning bg-opacity-10 text-warning">
Pending
</span>

<?php elseif($d['status']=='approved'): ?>

<span class="badge bg-success bg-opacity-10 text-success">
Approved
</span>

<?php else: ?>

<span class="badge bg-danger bg-opacity-10 text-danger">
Rejected
</span>

<?php endif; ?>

</td>


<td>

<?php if($d['status']=='pending'): ?>

<a href="approve_member.php?id=<?= $d['id_daftar'] ?>"
class="btn btn-sm btn-outline-success rounded-pill m-1"
onclick="return confirm('Setujui pendaftaran ini?')">

<i class="bi bi-check-circle"></i>

</a>

<a href="reject_member.php?id=<?= $d['id_daftar'] ?>"
class="btn btn-sm btn-outline-danger rounded-pill m-1"
onclick="return confirm('Tolak pendaftaran ini?')">

<i class="bi bi-x-circle"></i>

</a>

<?php else: ?>

<span class="text-muted small">Selesai</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>
<td colspan="7" class="text-center text-muted py-4">
Belum ada pendaftaran member
</td>
</tr>

<?php endif; ?>

</tbody>
</table>


</div>
</div>
</div>

</div>
</div>
</section>

<?php include 'layout/footer.php'; ?>
