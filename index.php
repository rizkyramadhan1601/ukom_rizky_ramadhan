  <?php
  require_once 'config/controller.php';
  include 'layout/header.php';

  $tanggal_awal  = $_GET['tanggal_awal'] ?? null;
  $tanggal_akhir = $_GET['tanggal_akhir'] ?? null;

  $limit = 10;
  $page  = isset($_GET['page_rekap']) ? max(1, (int)$_GET['page_rekap']) : 1;
  $offset = ($page - 1) * $limit;

  $transaksis = get_rekap_transaksi(
      $tanggal_awal,
      $tanggal_akhir,
      $limit,
      $offset
  );

  $total_data = count_rekap_transaksi($tanggal_awal, $tanggal_akhir);
  $total_page = ceil($total_data / $limit);
  ?>


    <main class="main">

      <!-- Hero Section -->
      <section id="hero" class="hero section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
                <div class="company-badge mb-4">
                  <i class="bi bi-cash-coin me-2"></i>
                  EZParking
                </div>

                <h1 class="mb-4">
                  Easy Parking<br>
                  Smart System<br>
                  <span class="accent-text">Smart Parking Solution</span>
                </h1>

                <p class="mb-4 mb-md-5">
                  Aplikasi Website yang Dirancang Untuk Memudahkan Pengelolaan Parkir Kendaraan pada Berbagai Jenis Tempat Parkir dengan Sistem yang Cepat dan Aman.
                </p>
                <?php if (in_array($role, ['petugas', 'admin'])): ?>
                  <div class="hero-buttons">
                    <?php if (in_array($role, ['petugas'])): ?>
                    <a href="ezparking.php" class="btn btn-primary m-2 me-0 me-sm-2 mx-1">Mulai Bekerja</a>
                    <?php endif; ?>
                    <a href="data_pendaftaran.php" class="btn btn-primary m-2 me-0 me-sm-2 mx-1">Kelola Member Baru</a>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
                <img src="assets-img/main-icon.webp" alt="Hero Image" class="img-fluid">
              </div>
            </div>
          </div>

          <div class="row stats-row gy-4 mt-5" data-aos="fade-up" data-aos-delay="500">

            <!-- Total Kendaraan -->
            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <div class="stat-icon">
                  <i class="bi bi-car-front-fill"></i>
                </div>
                <div class="stat-content">
                  <h4><?= $stats['kendaraan'] ?></h4>
                  <p class="mb-0">Total Kendaraan Terdaftar</p>
                </div>
              </div>
            </div>

            <!-- Total Area Parkir -->
            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <div class="stat-icon">
                  <i class="bi bi-signpost-2-fill"></i>
                </div>
                <div class="stat-content">
                  <h4><?= $stats['area'] ?></h4>
                  <p class="mb-0">Total Area Parkir</p>
                </div>
              </div>
            </div>

            <!-- Total Kapasitas -->
            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <div class="stat-icon">
                  <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>
                <div class="stat-content">
                  <h4><?= $stats['kapasitas'] ?></h4>
                  <p class="mb-0">Total Kapasitas Parkir</p>
                </div>
              </div>
            </div>

            <!-- Total Penghasilan -->
            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <div class="stat-icon">
                  <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                  <h4>
                    Rp <?= number_format($stats['penghasilan'], 0, ',', '.') ?>
                  </h4>
                  <p class="mb-0">Total Penghasilan Parkir</p>
                </div>
              </div>
            </div>
        </div>
      </section><!-- /Hero Section -->

  <?php if (in_array($role, ['owner','petugas'])): ?>
        <!-- REKAP Section -->
            <section id="rekap-transaksi" class="about section">
              <?php
                  // ===============================
                  // PAGINATION KHUSUS REKAP
                  // ===============================
                  $limit_rekap = 10;

                  // parameter unik (ANTI BENTROK)
                  $page_rekap = isset($_GET['page_rekap']) && is_numeric($_GET['page_rekap'])
                      ? (int) $_GET['page_rekap']
                      : 1;

                  if ($page_rekap < 1) $page_rekap = 1;

                  $offset_rekap = ($page_rekap - 1) * $limit_rekap;

                  // filter tanggal
                  $tanggal_awal  = $_GET['tanggal_awal'] ?? null;
                  $tanggal_akhir = $_GET['tanggal_akhir'] ?? null;

                  // ambil data
                  $transaksis = get_rekap_transaksi(
                      $tanggal_awal,
                      $tanggal_akhir,
                      $limit_rekap,
                      $offset_rekap
                  );

                  // total data
                  $total_data_rekap = count_rekap_transaksi($tanggal_awal, $tanggal_akhir);
                  $total_page_rekap = ceil($total_data_rekap / $limit_rekap);
                ?>
        <div class="container mt-4">
          <div class="container section-title" data-aos="fade-up">
            <h2>Rekap Transaksi</h2>
            <p>Data transaksi parkir berdasarkan rentang waktu</p>
          </div>
          <?php if ($tanggal_awal && $tanggal_akhir): ?>
            <div class="text-center mb-3">
              <span class="badge bg-primary bg-opacity-10 text-primary">
                Periode: <?= $tanggal_awal ?> s/d <?= $tanggal_akhir ?>
              </span>
            </div>
          <?php endif; ?>


          <div class="card shadow-sm border-0 mb-4" data-aos="fade-up">
            <div class="card-body">
              <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">
                  <label class="form-label">Dari Tanggal</label>
                  <input type="date" name="tanggal_awal"
                        class="form-control"
                        value="<?= $_GET['tanggal_awal'] ?? '' ?>">
                </div>

                <div class="col-md-4">
                  <label class="form-label">Sampai Tanggal</label>
                  <input type="date" name="tanggal_akhir"
                        class="form-control"
                        value="<?= $_GET['tanggal_akhir'] ?? '' ?>">
                </div>

                <div class="col-md-4">
                  <button type="submit" class="btn btn-primary rounded-pill w-100">
                    <i class="bi bi-search"></i> Tampilkan
                  </button>
                </div>

              </form>
            </div>
          </div>

          <div class="card shadow-sm border-0" data-aos="fade-up">
            <div class="card-body table-responsive">

              <table class="table align-middle mb-0">
                <thead class="table-light text-center">
                  <tr>
                    <th>No</th>
                    <th>Kode Member</th>
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Durasi (Jam)</th>
                    <th>Total Bayar</th>
                  </tr>
                </thead>

                <tbody>
                <?php if (!empty($transaksis)) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($transaksis as $trx) : ?>
                      <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><?= $trx['kode_member'] ?></td>
                        <td><?= $trx['plat_nomor'] ?></td>
                        <td>
                          <span class="badge bg-info bg-opacity-10 text-info">
                            <?= ucfirst($trx['jenis_kendaraan']) ?>
                          </span>
                        </td>
                        <td><?= $trx['waktu_masuk'] ?></td>
                        <td><?= $trx['waktu_keluar'] ?></td>
                        <td><?= $trx['durasi_jam'] ?></td>
                        <td>
                          <strong>
                            Rp <?= number_format($trx['biaya_total'], 0, ',', '.') ?>
                          </strong>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                      <td colspan="8" class="text-center text-muted">
                        Data transaksi tidak ditemukan
                      </td>
                    </tr>
                <?php endif; ?>
                </tbody>
              </table>

            </div>
            <?php if ($total_page_rekap > 1): ?>
              <nav class="mt-4">
                <ul class="pagination justify-content-center">

                  <!-- PREV -->
                  <li class="page-item <?= ($page_rekap <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                      href="?<?= http_build_query(array_merge($_GET, [
                          'page_rekap' => $page_rekap - 1
                      ])) ?>">
                      &laquo;
                    </a>
                  </li>

                  <!-- NUMBER -->
                  <?php for ($i = 1; $i <= $total_page_rekap; $i++): ?>
                    <li class="page-item <?= ($i == $page_rekap) ? 'active' : '' ?>">
                      <a class="page-link"
                        href="?<?= http_build_query(array_merge($_GET, [
                            'page_rekap' => $i
                        ])) ?>">
                        <?= $i ?>
                      </a>
                    </li>
                  <?php endfor; ?>

                  <!-- NEXT -->
                  <li class="page-item <?= ($page_rekap >= $total_page_rekap) ? 'disabled' : '' ?>">
                    <a class="page-link"
                      href="?<?= http_build_query(array_merge($_GET, [
                          'page_rekap' => $page_rekap + 1
                      ])) ?>">
                      &raquo;
                    </a>
                  </li>

                </ul>
              </nav>
            <?php endif; ?>

          </div>

        </div>
      </section>
  <?php endif; ?>
  <!-- /REKAP Section -->


  <!-- USER Section -->
  <?php if (in_array($role, ['admin'])): ?>

      <section id="data-users" class="about section">

        <div class="container mt-4">
        
        <div class="container section-title" data-aos="fade-up">
          <h2>Data Users</h2>
          <p>Daftar Users Aktif Pada EZParking</p>
        </div>
          <div class="d-flex justify-content-start mb-3" data-aos="fade-up">
            <button class="btn btn-sm btn-outline-primary rounded-pill"
                data-bs-toggle="modal"
                data-bs-target="#modalTambahUser">
                <i class="bi bi-plus-circle"></i> Tambah User
            </button>
            </div>
              <div class="card shadow-sm border-0 mt-4" data-aos="fade-up">
                  <div class="card-body table-responsive">
                      <table class="table align-middle mb-0">
                          <thead class="table-light text-center">
                              <tr>
                                  <th>No</th>
                                  <th>Nama Lengkap</th>
                                  <th>Username</th>
                                  <th>Role</th>
                                  <th>Status</th>
                                  <th>Tindakan</th>
                              </tr>
                          </thead>
                          <tbody>
                          <?php if (!empty($users)) : ?>
                              <?php $no = 1; ?>
                              <?php foreach ($users as $user) : ?>
                                  <tr class="text-center">
                                      <td><?= $no++ ?></td>
                                      <td><?= $user['nama_lengkap'] ?></td>
                                      <td><?= $user['username'] ?></td>
                                      <td>
                                          <span class="badge bg-primary bg-opacity-10 text-primary">
                                              <?= ucfirst($user['role']) ?>
                                          </span>
                                      </td>
                                      <td>
                                          <?php if ($user['status_aktif'] == 1): ?>
                                              <span class="badge bg-success bg-opacity-10 text-success">
                                                  Aktif
                                              </span>
                                          <?php else: ?>
                                              <span class="badge bg-danger bg-opacity-10 text-danger">
                                                  Nonaktif
                                              </span>
                                          <?php endif; ?>
                                      </td>
                                      <td>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill  m-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditUser"
                                                data-id="<?= $user['id_user'] ?>"
                                                data-nama="<?= $user['nama_lengkap'] ?>"
                                                data-username="<?= $user['username'] ?>"
                                                data-role="<?= $user['role'] ?>"
                                                data-status="<?= $user['status_aktif'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger rounded-pill m-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapusUser"
                                                data-id="<?= $user['id_user'] ?>">
                                          <i class="bi bi-trash"></i>
                                        </button>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php else : ?>
                              <tr>
                                  <td colspan="5" class="text-center text-muted">
                                      Data user tidak tersedia
                                  </td>
                              </tr>
                          <?php endif; ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>


          <!-- MODAL EDIT USER -->
          <div class="modal fade" id="modalEditUser" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                  <form method="post">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit User</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                      <input type="hidden" name="id_user" id="edit_id_user">

                      <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit_nama" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label>Role</label>
                        <select name="role" id="edit_role" class="form-select">
                          <option value="admin">Admin</option>
                          <option value="petugas">Petugas</option>
                          <option value="owner">Owner</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label>Status</label>
                        <select name="status_aktif" id="edit_status" class="form-select">
                          <option value="1">Aktif</option>
                          <option value="0">Nonaktif</option>
                        </select>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" name="update_user" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>

            <!-- MODAL TAMBAH USER -->
            <div class="modal fade" id="modalTambahUser" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">

                    <form method="post">
                      <div class="modal-header">
                        <h5 class="modal-title">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">

                        <div class="mb-3">
                          <label>Nama Lengkap</label>
                          <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>

                        <div class="mb-3">
                          <label>Username</label>
                          <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                          <label>Password</label>
                          <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                          <label>Role</label>
                          <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="owner">Owner</option>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label>Status</label>
                          <select name="status_aktif" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                          </select>
                        </div>

                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_user" class="btn btn-success">
                          Simpan
                        </button>
                      </div>
                    </form>

                  </div>
                </div>
              </div>

              <!-- MODAL HAPUS USER -->
              <div class="modal fade" id="modalHapusUser" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                      <form method="post">
                        <div class="modal-header">
                          <h5 class="modal-title">Hapus User</h5>
                        </div>

                        <div class="modal-body">
                          <input type="hidden" name="id_user" id="hapus_id_user">
                          <p>Yakin ingin menghapus user ini?</p>
                        </div>

                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" name="hapus_user" class="btn btn-danger">Hapus</button>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
      </section>
      <!-- /USER Section -->

      <!-- TARIF Section -->
      <section id="tarif" class="testimonials section light-background">
        <div class="container section-title" data-aos="fade-up">
          <h2>Daftar Tarif Parkir</h2>
          <p>Tarif Sesuai Kendaraan dan Sewaktu Waktu Dapat Berubah</p>
        </div>
        <div class="container">
          <div class="row g-5">
          <div class="d-flex justify-content-start mb-3" data-aos="fade-up">
              <button class="btn btn-sm btn-outline-primary rounded-pill"
                  data-bs-toggle="modal"
                  data-bs-target="#modalTambahTarif">
                  <i class="bi bi-plus-circle"></i> Tambah Tarif
              </button>
          </div>
            <div class="card shadow-sm border-0 mt-4" data-aos="fade-up">
                  <div class="card-body table-responsive">
                      <table class="table align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Jenis Kendaraan</th>
                                <th>Tarif Per Jam</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php if (!empty($tarifs)) : ?>
                              <?php $no = 1; ?>
                              <?php foreach ($tarifs as $tarif) : ?>
                                  <tr class="text-center">
                                      <td><?= $no++ ?></td>
                                      <td>
                                          <span>
                                              <?= ucfirst($tarif['jenis_kendaraan']) ?>
                                          </span>
                                      </td>
                                      <td>
                                          Rp <?= number_format($tarif['tarif_per_jam'], 0, ',', '.') ?>
                                      </td>
                                      <td>
                                          <button class="btn btn-sm btn-outline-primary rounded-pill m-1"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#modalEditTarif"
                                                  data-id="<?= $tarif['id_tarif'] ?>"
                                                  data-jenis="<?= $tarif['jenis_kendaraan'] ?>"
                                                  data-tarif="<?= $tarif['tarif_per_jam'] ?>">
                                              <i class="bi bi-pencil"></i>
                                          </button>

                                          <button class="btn btn-sm btn-outline-danger rounded-pill m-1"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#modalHapusTarif"
                                                  data-id="<?= $tarif['id_tarif'] ?>"
                                                  data-jenis="<?= $tarif['jenis_kendaraan'] ?>">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                            <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Data tarif tidak tersedia
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                  </div>
              </div>

          </div>

        </div>

      </section>
      <!-- /TARIF Section -->

      <!-- Modal Tambah Tarif -->
      <div class="modal fade" id="modalTambahTarif" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">

            <form method="post">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Tarif Parkir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">

                <div class="mb-3">
                  <label class="form-label">Jenis Kendaraan</label>
                  <select name="jenis_kendaraan" class="form-select" required>
                    <option value="">-- Pilih Jenis Kendaraan --</option>
                    <option value="motor">Motor</option>
                    <option value="mobil">Mobil</option>
                    <option value="lainnya">Lainnya</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Tarif Per Jam</label>
                  <input type="number"
                      name="tarif_per_jam"
                      class="form-control"
                      placeholder="Contoh: 5000"
                      required>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  Batal
                </button>
                <button type="submit" name="tambah_tarif" class="btn btn-success">
                  Simpan
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>

  <!-- Modal Edit Tarif -->
  <div class="modal fade" id="modalEditTarif" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title">Edit Tarif Parkir</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <input type="hidden" name="id_tarif" id="edit_id_tarif">

            <div class="mb-3">
              <label class="form-label">Tarif Per Jam</label>
              <input type="number"
                    name="tarif_per_jam"
                    id="edit_tarif_per_jam"
                    class="form-control"
                    required>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Batal
              </button>
              <button type="submit" name="update_tarif" class="btn btn-primary">
                Update
              </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Hapus Tarif -->
  <div class="modal fade" id="modalHapusTarif" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Hapus Tarif Parkir</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <!-- id_tarif -->
            <input type="hidden" name="id_tarif" id="hapus_id_tarif">

            <p class="mb-0">
              Apakah Anda yakin ingin menghapus tarif kendaraan
              <strong id="hapus_jenis_kendaraan"></strong>?
            </p>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="submit" name="hapus_tarif" class="btn btn-danger">
              Ya, Hapus
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>

  <!-- BATAS SECTION -->

  <!-- AREA PARKIR Section -->
      <section id="area" class="about section">
        <div class="container mt-4">
        <div class="container section-title" data-aos="fade-up">
          <h2>Data Area Parkir</h2>
          <p>Daftar Area Parkir Yang Tersedia</p>
        </div>
          <div class="d-flex justify-content-start mb-3" data-aos="fade-up">
            <button class="btn btn-sm btn-outline-primary rounded-pill"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambahArea">
                    <i class="bi bi-plus-circle"></i> Tambah Area
            </button>
            </div>

              <div class="card shadow-sm border-0 mt-4" data-aos="fade-up">
                  <div class="card-body table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Area</th>
                                    <th>Kapasitas</th>
                                    <th>Terisi</th>
                                    <th>Sisa Slot</th>
                                    <th>Status</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>

                            <tbody>
                              <?php if (!empty($areas)) : ?>
                                  <?php foreach ($areas as $kategori => $listArea) : ?>
                                      <tr>
                                          <td colspan="7" class="fw-semibold text-start bg-light">
                                              <?= $kategori ?>
                                          </td>
                                      </tr>

                                      <?php $no = 1; ?>
                                      <?php foreach ($listArea as $area) : ?>
                                      <tr class="text-center">
                                          <td><?= $no++ ?></td>

                                          <td><?= $area['nama_area'] ?></td>

                                          <td>
                                              <span class="badge bg-primary bg-opacity-10 text-primary">
                                                  <?= $area['kapasitas'] ?>
                                              </span>
                                          </td>

                                          <td>
                                              <span class="badge bg-warning bg-opacity-10 text-warning">
                                                  <?= $area['terisi'] ?>
                                              </span>
                                          </td>

                                          <td>
                                              <?php if ($area['sisa_slot'] > 0): ?>
                                                  <span class="badge bg-success bg-opacity-10 text-success">
                                                      <?= $area['sisa_slot'] ?>
                                                  </span>
                                              <?php else: ?>
                                                  <span class="badge bg-danger bg-opacity-10 text-danger">
                                                      0
                                                  </span>
                                              <?php endif; ?>
                                          </td>

                                          <td>
                                              <?php if ($area['sisa_slot'] > 0): ?>
                                                  <span class="badge bg-success bg-opacity-10 text-success">
                                                      Tersedia
                                                  </span>
                                              <?php else: ?>
                                                  <span class="badge bg-danger bg-opacity-10 text-danger">
                                                      Penuh
                                                  </span>
                                              <?php endif; ?>
                                          </td>
                                          <td>
                                              <button class="btn btn-sm btn-outline-primary rounded-pill m-1"
                                                      data-bs-toggle="modal"
                                                      data-bs-target="#modalEditArea"
                                                      data-id="<?= $area['id_area'] ?>"
                                                      data-nama="<?= $area['nama_area'] ?>"
                                                      data-kapasitas="<?= $area['kapasitas'] ?>">
                                                  <i class="bi bi-pencil"></i>
                                              </button>

                                              <button class="btn btn-sm btn-outline-danger rounded-pill m-1"
                                                      data-bs-toggle="modal"
                                                      data-bs-target="#modalHapusArea"
                                                      data-id="<?= $area['id_area'] ?>"
                                                      data-nama="<?= $area['nama_area'] ?>">
                                                  <i class="bi bi-trash"></i>
                                              </button>
                                          </td>
                                      </tr>
                                      <?php endforeach; ?>

                                  <?php endforeach; ?>
                              <?php else : ?>
                              <tr>
                                  <td colspan="7" class="text-center text-muted">
                                      Data area parkir tidak tersedia
                                  </td>
                              </tr>
                              <?php endif; ?>
                            </tbody>
                        </table>
                  </div>
              </div>
          </div>
          <!-- Modal Tambah Area Parkir -->
          <div class="modal fade" id="modalTambahArea" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">

                <form method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Tambah Area Parkir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                    <div class="mb-3">
                      <label class="form-label">Nama Area</label>
                      <input type="text"
                            name="nama_area"
                            class="form-control"
                            placeholder="Contoh: Area Motor C"
                            required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Kapasitas</label>
                      <input type="number"
                            name="kapasitas"
                            class="form-control"
                            min="1"
                            placeholder="Contoh: 50"
                            required>
                    </div>

                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Batal
                    </button>
                    <button type="submit" name="tambah_area" class="btn btn-success">
                      Simpan
                    </button>
                  </div>

                </form>

              </div>
            </div>
          </div>

          <!-- Modal Edit Area Parkir -->
          <div class="modal fade" id="modalEditArea" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">

                <form method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Area Parkir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                    <!-- id_area -->
                    <input type="hidden" name="id_area" id="edit_id_area">

                    <div class="mb-3">
                      <label class="form-label">Nama Area</label>
                      <input type="text"
                            name="nama_area"
                            id="edit_nama_area"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Kapasitas</label>
                      <input type="number"
                            name="kapasitas"
                            id="edit_kapasitas"
                            class="form-control"
                            min="1"
                            required>
                    </div>

                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Batal
                    </button>
                    <button type="submit" name="update_area" class="btn btn-primary">
                      Update
                    </button>
                  </div>

                </form>

              </div>
            </div>
          </div>

          <!-- Modal Hapus Area Parkir -->
          <div class="modal fade" id="modalHapusArea" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">

                <form method="post">
                  <div class="modal-header">
                    <h5 class="modal-title text-danger">Hapus Area Parkir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                    <input type="hidden" name="id_area" id="hapus_id_area">

                    <p class="mb-0">
                      Apakah Anda yakin ingin menghapus area parkir
                      <strong id="hapus_nama_area"></strong>?
                    </p>

                    <small class="text-muted">
                      Data yang dihapus tidak dapat dikembalikan.
                    </small>

                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Batal
                    </button>
                    <button type="submit" name="hapus_area" class="btn btn-danger">
                      Ya, Hapus
                    </button>
                  </div>

                </form>

              </div>
            </div>
          </div>
      </section>
  <!-- /AREA Section -->
  <?php endif; ?>


  <?php if (in_array($role, ['admin', 'petugas'])): ?>
  <!-- KENDARAAAN Section -->
      <?php
        $limit_kendaraan = 5;

        $total_data_kendaraan = mysqli_num_rows(
            mysqli_query($koneksi, "SELECT * FROM tb_kendaraan")
        );

        $total_page_kendaraan = ceil($total_data_kendaraan / $limit_kendaraan);

        $page_kendaraan = isset($_GET['page_kendaraan']) ? (int)$_GET['page_kendaraan'] : 1;
        if ($page_kendaraan < 1) $page_kendaraan = 1;

        $offset_kendaraan = ($page_kendaraan - 1) * $limit_kendaraan;

        $kendaraans = mysqli_query($koneksi, "
            SELECT k.*, u.nama_lengkap
            FROM tb_kendaraan k
            LEFT JOIN tb_user u ON u.id_user = k.id_user
            ORDER BY k.id_kendaraan DESC
            LIMIT $limit_kendaraan OFFSET $offset_kendaraan
        ");
      ?>

  <section id="kendaraan" class="testimonials section light-background">

    <div class="container mt-4">

      <div class="container section-title" data-aos="fade-up">
        <h2>Data Kendaraan</h2>
        <p>Daftar Kendaraan Terdaftar</p>
      </div>

      <div class="d-flex justify-content-start mb-3" data-aos="fade-up">
        <button class="btn btn-sm btn-outline-primary rounded-pill"
                data-bs-toggle="modal"
                data-bs-target="#modalTambahKendaraan">
            <i class="bi bi-plus-circle"></i> Tambah Kendaraan
        </button>
      </div>

      <div class="card shadow-sm border-0 mt-4" data-aos="fade-up">
        <div class="card-body table-responsive">

          <table class="table align-middle mb-0">
            <thead class="table-light text-center">
            <tr>
              <th>No</th>
              <th>Kode Member</th>
              <th>Plat Nomor</th>
              <th>Jenis Kendaraan</th>
              <th>Warna</th>
              <th>Pemilik</th>
              <th>Petugas</th>
              <th>QR Code</th>
              <th>Tindakan</th>
            </tr>
            </thead>

          <tbody>
              <?php if (!empty($kendaraans)) : ?>
              <?php $no = 1; ?>
              <?php foreach ($kendaraans as $kendaraan) : ?>
              <tr class="text-center">

                <td><?= $no++ ?></td>

                <td>
                  <span class="badge bg-success bg-opacity-10 text-success">
                    <?= $kendaraan['kode_member'] ?>
                  </span>
                </td>

                <td>
                  <span class="badge bg-dark bg-opacity-10 text-dark">
                    <?= $kendaraan['plat_nomor'] ?>
                  </span>
                </td>

                <td>
                  <span class="badge bg-primary bg-opacity-10 text-primary">
                    <?= ucfirst($kendaraan['jenis_kendaraan']) ?>
                  </span>
                </td>

                <td><?= $kendaraan['warna'] ?? '-' ?></td>
                <td><?= $kendaraan['pemilik'] ?? '-' ?></td>
                <td><?= $kendaraan['nama_lengkap'] ?? '-' ?></td>

              <td style="width:120px; text-align:center;">
                <img
                  src="http://localhost/ukom_rizky/assets/qr-image.php?text=<?= urlencode($kendaraan['kode_member']) ?>&v=<?= time() ?>"
                  width="80"
                  height="80"
                  style="display:block;margin:auto;border:1px solid red;"
                >
                <small><?= $kendaraan['kode_member'] ?></small>
              </td>

                <td>
                  <button class="btn btn-sm btn-outline-primary rounded-pill m-1"
                          data-bs-toggle="modal"
                          data-bs-target="#modalEditKendaraan"
                          data-id="<?= $kendaraan['id_kendaraan'] ?>"
                          data-kode="<?= $kendaraan['kode_member'] ?>"
                          data-plat="<?= $kendaraan['plat_nomor'] ?>"
                          data-jenis="<?= $kendaraan['jenis_kendaraan'] ?>"
                          data-warna="<?= $kendaraan['warna'] ?>"
                          data-pemilik="<?= $kendaraan['pemilik'] ?>">
                      <i class="bi bi-pencil"></i>
                  </button>

                  <button class="btn btn-sm btn-outline-danger rounded-pill m-1"
                          data-bs-toggle="modal"
                          data-bs-target="#modalHapusKendaraan"
                          data-id="<?= $kendaraan['id_kendaraan'] ?>"
                          data-plat="<?= $kendaraan['plat_nomor'] ?>">
                      <i class="bi bi-trash"></i>
                  </button>
                </td>

              </tr>
              <?php endforeach; ?>
              <?php else : ?>
              <tr>
                <td colspan="9" class="text-center text-muted">
                  Data kendaraan tidak tersedia
                </td>
              </tr>
              <?php endif; ?>
          </tbody>
          </table>

        </div>

        <!-- PAGINATION TETAP -->
        <nav aria-label="Pagination Kendaraan" class="mt-4">
          <ul class="pagination justify-content-center">

            <li class="page-item <?= ($page_kendaraan <= 1) ? 'disabled' : '' ?>">
              <a class="page-link" href="?page_kendaraan=<?= $page_kendaraan - 1 ?>#kendaraan">
                Sebelumnya
              </a>
            </li>

            <?php for ($i = 1; $i <= $total_page_kendaraan; $i++) : ?>
            <li class="page-item <?= ($page_kendaraan == $i) ? 'active' : '' ?>">
              <a class="page-link" href="?page_kendaraan=<?= $i ?>#kendaraan">
                <?= $i ?>
              </a>
            </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page_kendaraan >= $total_page_kendaraan) ? 'disabled' : '' ?>">
              <a class="page-link" href="?page_kendaraan=<?= $page_kendaraan + 1 ?>#kendaraan">
                Selanjutnya
              </a>
            </li>

          </ul>
        </nav>

      </div>
    </div>
  </section>



  <!-- Modal Tambah Kendaraan -->
  <div class="modal fade" id="modalTambahKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Kendaraan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <div class="mb-3">
              <label class="form-label">Plat Nomor</label>
              <input type="text"
                    name="plat_nomor"
                    class="form-control"
                    placeholder="Contoh: B 1234 XYZ"
                    required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jenis Kendaraan</label>
              <select name="jenis_kendaraan" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="motor">Motor</option>
                <option value="mobil">Mobil</option>
                <option value="lainnya">Lainnya</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Warna</label>
              <input type="text"
                    name="warna"
                    class="form-control"
                    placeholder="Contoh: Hitam">
            </div>

            <div class="mb-3">
              <label class="form-label">Pemilik</label>
              <input type="text"
                    name="pemilik"
                    class="form-control"
                    placeholder="Nama Pemilik">
            </div>

            <div class="alert alert-info small mb-0">
              <i class="bi bi-info-circle"></i>
              Kode Member akan dibuat otomatis oleh sistem
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="submit" name="tambah_kendaraan" class="btn btn-success">
              Simpan
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>


  <!-- Modal Edit Kendaraan -->
  <div class="modal fade" id="modalEditKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title">Edit Kendaraan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <!-- id_kendaraan -->
            <input type="hidden" name="id_kendaraan" id="edit_id_kendaraan">

            <div class="mb-3">
              <label class="form-label">Kode Member</label>
              <input type="text"
                    name="kode_member"
                    id="edit_kode_member"
                    class="form-control"
                    readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Plat Nomor</label>
              <input type="text"
                    name="plat_nomor"
                    id="edit_plat_nomor"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jenis Kendaraan</label>
              <select name="jenis_kendaraan"
                      id="edit_jenis_kendaraan"
                      class="form-select"
                      required>
                <option value="motor">Motor</option>
                <option value="mobil">Mobil</option>
                <option value="lainnya">Lainnya</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Warna</label>
              <input type="text"
                    name="warna"
                    id="edit_warna"
                    class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Pemilik</label>
              <input type="text"
                    name="pemilik"
                    id="edit_pemilik"
                    class="form-control">
            </div>

            <div class="alert alert-warning small mb-0">
              <i class="bi bi-shield-lock"></i>
              Kode Member tidak dapat diubah
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="submit" name="update_kendaraan" class="btn btn-primary">
              Update
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>


      <!-- Modal Hapus Kendaraan -->
      <div class="modal fade" id="modalHapusKendaraan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">

            <form method="post">
              <div class="modal-header">
                <h5 class="modal-title text-danger">Hapus Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">

                <!-- id_kendaraan -->
                <input type="hidden" name="id_kendaraan" id="hapus_id_kendaraan">

                <p class="mb-0">
                  Apakah Anda yakin ingin menghapus kendaraan dengan plat
                  <strong id="hapus_plat_kendaraan"></strong>?
                </p>

                <small class="text-muted">
                  Data yang dihapus tidak dapat dikembalikan.
                </small>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  Batal
                </button>
                <button type="submit" name="hapus_kendaraan" class="btn btn-danger">
                  Ya, Hapus
                </button>
              </div>

            </form>

          </div>
        </div>
      </div>
      <!-- /KENDARAAN Section -->
  <?php endif; ?>


    <?php if (in_array($role, ['admin'])): ?>
      <!-- LOG AKTIVITAS Section -->
      <?php
        include 'config/app.php';
        $limit_log = 10;

        $total_data_log = mysqli_num_rows(
            mysqli_query($koneksi, "SELECT * FROM tb_log_aktivitas")
        );

        $total_page_log = ceil($total_data_log / $limit_log);

        $page_log = isset($_GET['page_log']) ? (int)$_GET['page_log'] : 1;
        if ($page_log < 1) $page_log = 1;

        $offset_log = ($page_log - 1) * $limit_log;

        $logs = mysqli_query($koneksi, "
            SELECT l.*, u.nama_lengkap
            FROM tb_log_aktivitas l
            LEFT JOIN tb_user u ON l.id_user = u.id_user
            ORDER BY l.waktu_aktivitas DESC
            LIMIT $limit_log OFFSET $offset_log
        ");
      ?>

  <section id="log-aktivitas" class="about section">
    <div class="container mt-4">

      <div class="container section-title" data-aos="fade-up">
        <h2>Riwayat Aktivitas</h2>
        <p>Riwayat Aktivitas Pengguna yang Tercatat Otomatis Oleh Sistem</p>
      </div>

      <div class="card shadow-sm border-0 mt-4" data-aos="fade-up">
        <div class="card-body table-responsive">

          <table class="table align-middle mb-0">
            <thead class="table-light text-center">
              <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Aktivitas</th>
                <th>Waktu</th>
              </tr>
            </thead>

            <tbody>
              <?php if (!empty($logs)) : ?>
                <?php $no = 1; ?>
                <?php foreach ($logs as $log) : ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                      <?= $log['nama_lengkap'] ?? '<span class="text-muted">System</span>' ?>
                    </td>
                    <td><?= $log['aktivitas'] ?></td>
                    <td class="text-center">
                      <?= date('d-m-Y H:i', strtotime($log['waktu_aktivitas'])) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="4" class="text-center text-muted">
                    Belum ada log aktivitas
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>

        </div>
      </div>
      <nav aria-label="Pagination Log Aktivitas" class="mt-4">
        <ul class="pagination justify-content-center">

          <li class="page-item <?= ($page_log <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page_log=<?= $page_log - 1 ?>#log-aktivitas">
              Sebelumnya
            </a>
          </li>

          <?php for ($i = 1; $i <= $total_page_log; $i++) : ?>
            <li class="page-item <?= ($page_log == $i) ? 'active' : '' ?>">
              <a class="page-link" href="?page_log=<?= $i ?>#log-aktivitas">
                <?= $i ?>
              </a>
            </li>
          <?php endfor; ?>

          <li class="page-item <?= ($page_log >= $total_page_log) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page_log=<?= $page_log + 1 ?>#log-aktivitas">
              Selanjutnya
            </a>
          </li>

        </ul>
      </nav>

    </div>
  </section>
  <!-- /LOG AKTIVITAS Section -->

  <?php endif; ?>
  </main>


  <?php include 'layout/footer.php'; ?>
  <script src="script-modal.js"></script>