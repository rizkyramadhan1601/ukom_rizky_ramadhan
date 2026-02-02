<?php
// ==================================================
// HELPER GLOBAL
// ==================================================

if (!function_exists('log_aktivitas')) {
    function log_aktivitas($aktivitas)
    {
        global $koneksi;

        if (!isset($_SESSION['id_user'])) {
            return;
        }
        // Ambil id_user dari session
        $id_user   = $_SESSION['id_user'];
        $aktivitas = mysqli_real_escape_string($koneksi, $aktivitas);

        //Masukkan ke tabel log aktivitas 
        mysqli_query($koneksi, "
            INSERT INTO tb_log_aktivitas (id_user, aktivitas, waktu_aktivitas)
            VALUES ('$id_user', '$aktivitas', NOW())
        ");
    }
}

if (!function_exists('query_log')) {
    function query_log($sql, $aktivitas)
    {
        global $koneksi;

        mysqli_query($koneksi, $sql);
        log_aktivitas($aktivitas);
    }
}

// ===============================
// CONTROLLER USER APLIKASI PARKIR
// ===============================

global $koneksi;
if (!isset($koneksi)) {
    include_once 'config/database.php'; 
}

// ===============================
// AUTH CONTROLLER (LOGIN & LOGOUT)
// ===============================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

// ===============================
// PROSES LOGIN
// ===============================
if (isset($_POST['login'])) {
    
    // Ambil data dari form
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    // Masukkan ke query
    $query = mysqli_query($koneksi, "
        SELECT * FROM tb_user 
        WHERE username = '$username' 
          AND status_aktif = 1
        LIMIT 1
    ");

    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {
            // Password benar, set session
            $_SESSION['login']    = true;
            $_SESSION['id_user']  = $user['id_user'];
            $_SESSION['nama']     = $user['nama_lengkap'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            echo "<script>
                alert('Login berhasil');
                window.location='index.php';
            </script>";
            exit;

        } else {
            echo "<script>alert('Password salah');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan / akun nonaktif');</script>";
    }
}

// ===============================
// PROSES LOGOUT
// ===============================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}


// ===============================
// AMBIL DATA REKAP TRANSAKSI
// ===============================
if (!function_exists('get_rekap_transaksi')) {
    function get_rekap_transaksi(
        $tanggal_awal = null,
        $tanggal_akhir = null,
        $limit = 10,
        $offset = 0 
    ) {
        global $koneksi;

        $where = "WHERE 1=1";
        // Filter tanggal
        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $tanggal_awal  = mysqli_real_escape_string($koneksi, $tanggal_awal);
            $tanggal_akhir = mysqli_real_escape_string($koneksi, $tanggal_akhir);

            $where .= " AND t.waktu_masuk
                        BETWEEN '$tanggal_awal 00:00:00'
                        AND '$tanggal_akhir 23:59:59'";
        }

        // Query utama
        $query = "
            SELECT 
                k.kode_member,
                k.plat_nomor,
                k.jenis_kendaraan,
                t.waktu_masuk,
                t.waktu_keluar,
                t.durasi_jam,
                t.biaya_total
            FROM tb_transaksi t
            JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
            $where
            ORDER BY t.waktu_masuk DESC
            LIMIT $limit OFFSET $offset
        ";

        $result = mysqli_query($koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }
}

// ===============================
// HITUNG TOTAL DATA REKAP
// ===============================
if (!function_exists('count_rekap_transaksi')) {
    function count_rekap_transaksi($tanggal_awal = null, $tanggal_akhir = null)
    {
        global $koneksi;

        $where = "WHERE 1=1";

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $tanggal_awal  = mysqli_real_escape_string($koneksi, $tanggal_awal);
            $tanggal_akhir = mysqli_real_escape_string($koneksi, $tanggal_akhir);

            $where .= " AND t.waktu_masuk
                        BETWEEN '$tanggal_awal 00:00:00'
                        AND '$tanggal_akhir 23:59:59'";
        }

        $query = "
            SELECT COUNT(*) AS total
            FROM tb_transaksi t
            JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
            $where
        ";

        $row = mysqli_fetch_assoc(mysqli_query($koneksi, $query));
        return (int) $row['total'];
    }
}




// ===============================
// DATA STATISTIK DASHBOARD
// ===============================
if (!function_exists('get_dashboard_stats')) {
    function get_dashboard_stats()
    {
        global $koneksi;

        // ===============================
        // Total kendaraan terdaftar
        // ===============================
        $q_kendaraan = mysqli_query($koneksi, "
            SELECT COUNT(*) AS total 
            FROM tb_kendaraan
        ");
        $total_kendaraan = mysqli_fetch_assoc($q_kendaraan)['total'] ?? 0;

        // ===============================
        // Total area parkir
        // ===============================
        $q_area = mysqli_query($koneksi, "
            SELECT COUNT(*) AS total 
            FROM tb_area_parkir
        ");
        $total_area = mysqli_fetch_assoc($q_area)['total'] ?? 0;

        // ===============================
        // Total kapasitas parkir
        // ===============================
        $q_kapasitas = mysqli_query($koneksi, "
            SELECT SUM(kapasitas) AS total 
            FROM tb_area_parkir
        ");
        $total_kapasitas = mysqli_fetch_assoc($q_kapasitas)['total'] ?? 0;

        // ===============================
        //  Total penghasilan parkir
        // ===============================
        $q_penghasilan = mysqli_query($koneksi, "
            SELECT SUM(biaya_total) AS total 
            FROM tb_transaksi
            WHERE status = 'keluar'
        ");
        $total_penghasilan = mysqli_fetch_assoc($q_penghasilan)['total'] ?? 0;

        // ===============================
        // Return data statistik
        // ===============================
        return [
            'kendaraan'   => (int) $total_kendaraan,
            'area'        => (int) $total_area,
            'kapasitas'   => (int) $total_kapasitas,
            'penghasilan' => (int) $total_penghasilan
        ];
    }
}



// ===============================
// AMBIL SEMUA USER
// ===============================
if (!function_exists('get_all_users')) {
    function get_all_users()
    {
        global $koneksi;

        $query  = "SELECT * FROM tb_user ORDER BY id_user DESC";
        $result = mysqli_query($koneksi, $query);

        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['status_text'] = ($row['status_aktif'] == 1) ? 'Aktif' : 'Nonaktif';
            $users[] = $row;
        }

        return $users;
    }
}

// ===============================
// AMBIL USER BERDASARKAN ID
// ===============================
if (!function_exists('get_user_by_id')) {
    function get_user_by_id($id_user)
    {
        global $koneksi;

        $id_user = mysqli_real_escape_string($koneksi, $id_user);
        $query   = "SELECT * FROM tb_user WHERE id_user = '$id_user'";
        $result  = mysqli_query($koneksi, $query);

        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $row['status_text'] = ($row['status_aktif'] == 1) ? 'Aktif' : 'Nonaktif';
        }

        return $row;
    }
}

// ===============================
// PROSES TAMBAH USER
// ===============================
if (isset($_POST['tambah_user'])) {
    global $koneksi;

    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);
    $status   = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $cek = mysqli_query($koneksi, "
        SELECT id_user FROM tb_user WHERE username = '$username'
    ");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Username sudah digunakan!';
        header("Location: index.php#data-users");
        exit;
    }

    query_log("
        INSERT INTO tb_user (nama_lengkap, username, password, role, status_aktif)
        VALUES ('$nama', '$username', '$password_hash', '$role', '$status')
    ", "Menambahkan user $username");

    $_SESSION['success'] = 'User berhasil ditambahkan';
    header("Location: index.php#data-users");
    exit;
}


// ===============================
// PROSES UPDATE USER
// ===============================
if (isset($_POST['update_user'])) {
    global $koneksi;

    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $usern   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role    = mysqli_real_escape_string($koneksi, $_POST['role']);
    $status  = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    query_log("
        UPDATE tb_user SET
            nama_lengkap = '$nama',
            username     = '$usern',
            role         = '$role',
            status_aktif = '$status'
        WHERE id_user = '$id_user'
    ", "Mengupdate user $usern");

    $_SESSION['success'] = 'Data user berhasil diupdate';

    header("Location: index.php#data-users");
    exit;
}


// ===============================
// PROSES HAPUS USER
// ===============================
if (isset($_POST['hapus_user'])) {
    global $koneksi;

    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);

    query_log("
        DELETE FROM tb_user WHERE id_user = '$id_user'
    ", "Menghapus user dengan ID $id_user");

    $_SESSION['success'] = 'Data user berhasil dihapus';

    header("Location: index.php#data-users");
    exit;
}

//BATAS SECTION

// ===============================
// AMBIL SEMUA TARIF
// ===============================
if (!function_exists('get_all_tarif')) {
    function get_all_tarif()
    {
        global $koneksi;

        $query  = "SELECT * FROM tb_tarif ORDER BY id_tarif ASC";
        $result = mysqli_query($koneksi, $query);

        $tarifs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['tarif_text'] = 'Rp ' . number_format($row['tarif_per_jam'], 0, ',', '.');
            $tarifs[] = $row;
        }

        return $tarifs;
    }
}

// ===============================
// AMBIL TARIF BERDASARKAN ID
// ===============================
if (!function_exists('get_tarif_by_id')) {
    function get_tarif_by_id($id_tarif)
    {
        global $koneksi;

        $id_tarif = mysqli_real_escape_string($koneksi, $id_tarif);
        $query    = "SELECT * FROM tb_tarif WHERE id_tarif = '$id_tarif'";
        $result   = mysqli_query($koneksi, $query);

        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $row['tarif_text'] = 'Rp ' . number_format($row['tarif_per_jam'], 0, ',', '.');
        }

        return $row;
    }
}


// ===============================
// PROSES TAMBAH TARIF
// ===============================
if (isset($_POST['tambah_tarif'])) {
    global $koneksi;

    $jenis_kendaraan = mysqli_real_escape_string($koneksi, $_POST['jenis_kendaraan']);
    $tarif_per_jam   = mysqli_real_escape_string($koneksi, $_POST['tarif_per_jam']);

    if ($jenis_kendaraan == '' || $tarif_per_jam == '') {
        $_SESSION['error'] = 'Data tidak boleh kosong!';
        header("Location: index.php?data-tarif");
        exit;
    }

    $cek = mysqli_query($koneksi, "
        SELECT id_tarif 
        FROM tb_tarif 
        WHERE jenis_kendaraan = '$jenis_kendaraan'
    ");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Tarif untuk jenis kendaraan tersebut sudah ada!';
        header("Location: index.php#tarif");
        exit;
    }

    query_log("
        INSERT INTO tb_tarif (jenis_kendaraan, tarif_per_jam)
        VALUES ('$jenis_kendaraan', '$tarif_per_jam')
    ", "Menambahkan tarif kendaraan $jenis_kendaraan");


    $_SESSION['success'] = 'Tarif parkir berhasil ditambahkan!';
    header("Location: index.php#tarif");
    exit;
}


// ===============================
// PROSES UPDATE TARIF
// ===============================
if (isset($_POST['update_tarif'])) {
    global $koneksi;

    $id_tarif      = mysqli_real_escape_string($koneksi, $_POST['id_tarif']);
    $tarif_per_jam = mysqli_real_escape_string($koneksi, $_POST['tarif_per_jam']);

    // Validasi
    if ($id_tarif == '' || $tarif_per_jam == '') {
        $_SESSION['error'] = 'Data tidak boleh kosong!';
        header("Location: index.php#tarif");
        exit;
    }

    // Ambil jenis kendaraan lama
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT jenis_kendaraan 
        FROM tb_tarif 
        WHERE id_tarif = '$id_tarif'
    "));

    // Update HANYA tarif
    query_log("
        UPDATE tb_tarif 
        SET tarif_per_jam = '$tarif_per_jam'
        WHERE id_tarif = '$id_tarif'
    ", "Mengupdate tarif parkir untuk jenis kendaraan {$data['jenis_kendaraan']}");

    $_SESSION['success'] = 'Tarif parkir berhasil diperbarui';
    header("Location: index.php#tarif");
    exit;
}


// ===============================
// PROSES HAPUS TARIF
// ===============================
if (isset($_POST['hapus_tarif'])) {
    global $koneksi;

    $id_tarif = mysqli_real_escape_string($koneksi, $_POST['id_tarif']);

    if ($id_tarif == '') {
        $_SESSION['error'] = 'ID tarif tidak valid!';
        header("Location: index.php#tarif");
        exit;
    }

    query_log("
        DELETE FROM tb_tarif 
        WHERE id_tarif = '$id_tarif'
    ", "Menghapus tarif parkir dengan ID $id_tarif");


    $_SESSION['success'] = 'Tarif parkir berhasil dihapus';
    header("Location: index.php#tarif");
    exit;
}



// ===============================
// AMBIL SEMUA AREA PARKIR
// ===============================
if (!function_exists('get_all_area_parkir_grouped')) {
    function get_all_area_parkir_grouped()
    {
        global $koneksi;

        $query  = "SELECT * FROM tb_area_parkir ORDER BY id_area ASC";
        $result = mysqli_query($koneksi, $query);

        $areas = [];

        while ($row = mysqli_fetch_assoc($result)) {

            // hitung sisa slot
            $row['sisa_slot'] = $row['kapasitas'] - $row['terisi'];

            // status area
            $row['status_area'] = ($row['sisa_slot'] > 0) ? 'Tersedia' : 'Penuh';

            // ambil kategori dari nama_area
            if (stripos($row['nama_area'], 'Motor') !== false) {
                $kategori = 'Motor';
            } elseif (stripos($row['nama_area'], 'Mobil') !== false) {
                $kategori = 'Mobil';
            } elseif (stripos($row['nama_area'], 'VIP') !== false) {
                $kategori = 'VIP';
            } else {
                $kategori = 'Lainnya';
            }

            $areas[$kategori][] = $row;
        }

        return $areas;
    }
}


// ===============================
// AMBIL AREA PARKIR BERDASARKAN ID
// ===============================
if (!function_exists('get_area_parkir_by_id')) {
    function get_area_parkir_by_id($id_area)
    {
        global $koneksi;

        $id_area = mysqli_real_escape_string($koneksi, $id_area);
        $query   = "SELECT * FROM tb_area_parkir WHERE id_area = '$id_area'";
        $result  = mysqli_query($koneksi, $query);

        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $row['sisa_slot']   = $row['kapasitas'] - $row['terisi'];
            $row['status_area'] = ($row['sisa_slot'] > 0) ? 'Tersedia' : 'Penuh';
        }

        return $row;
    }
}


// ===============================
// PROSES TAMBAH AREA PARKIR
// ===============================
if (isset($_POST['tambah_area'])) {
    global $koneksi;

    $nama_area = mysqli_real_escape_string($koneksi, $_POST['nama_area']);
    $kapasitas = mysqli_real_escape_string($koneksi, $_POST['kapasitas']);

    if ($nama_area == '' || $kapasitas == '') {
        $_SESSION['error'] = 'Data tidak boleh kosong!';
        header("Location: index.php#area");
        exit;
    }

    $cek = mysqli_query($koneksi, "
        SELECT id_area 
        FROM tb_area_parkir 
        WHERE nama_area = '$nama_area'
    ");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Nama area parkir sudah ada!';
        header("Location: index.php#area");
        exit;
    }

    query_log("
        INSERT INTO tb_area_parkir (nama_area, kapasitas, terisi)
        VALUES ('$nama_area', '$kapasitas', 0)
    ", "Menambahkan area parkir baru dengan nama $nama_area dan kapasitas $kapasitas");


    $_SESSION['success'] = 'Area parkir berhasil ditambahkan';
    header("Location: index.php#area");
    exit;
}



// ===============================
// PROSES UPDATE AREA PARKIR
// ===============================
if (isset($_POST['update_area'])) {
    global $koneksi;

    $id_area   = mysqli_real_escape_string($koneksi, $_POST['id_area']);
    $nama_area = mysqli_real_escape_string($koneksi, $_POST['nama_area']);
    $kapasitas = mysqli_real_escape_string($koneksi, $_POST['kapasitas']);

    // Validasi
    if ($id_area == '' || $nama_area == '' || $kapasitas == '') {
        $_SESSION['error'] = 'Data tidak boleh kosong!';
        header("Location: index.php#area");
        exit;
    }

    // Cegah duplikat nama area
    $cek = mysqli_query($koneksi, "
        SELECT id_area 
        FROM tb_area_parkir 
        WHERE nama_area = '$nama_area'
        AND id_area != '$id_area'
    ");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Nama area parkir sudah digunakan!';
        header("Location: index.php#area");
        exit;
    }

    // Update data
    query_log("
        UPDATE tb_area_parkir
        SET nama_area = '$nama_area',
            kapasitas = '$kapasitas'
        WHERE id_area = '$id_area'
    ", "Mengubah data area parkir dengan ID $id_area (Nama: $nama_area, Kapasitas: $kapasitas)");


    $_SESSION['success'] = 'Area parkir berhasil diperbarui';
    header("Location: index.php#area");
    exit;
}


// ===============================
// PROSES HAPUS AREA PARKIR
// ===============================
if (isset($_POST['hapus_area'])) {
    global $koneksi;

    $id_area = mysqli_real_escape_string($koneksi, $_POST['id_area']);

    if ($id_area == '') {
        $_SESSION['error'] = 'ID area tidak valid!';
        header("Location: index.php#area");
        exit;
    }

    $cek = mysqli_query($koneksi, "
        SELECT terisi 
        FROM tb_area_parkir 
        WHERE id_area = '$id_area'
    ");
    $row = mysqli_fetch_assoc($cek);

    if ($row && $row['terisi'] > 0) {
        $_SESSION['error'] = 'Area parkir tidak bisa dihapus karena masih terisi!';
        header("Location: index.php#area");
        exit;
    }

    // Hapus data
    query_log("
        DELETE FROM tb_area_parkir 
        WHERE id_area = '$id_area'
    ", "Menghapus area parkir dengan ID $id_area");


    $_SESSION['success'] = 'Area parkir berhasil dihapus';
    header("Location: index.php#area");
    exit;
}


// ===============================
// AMBIL SEMUA DATA KENDARAAN
// ===============================
if (!function_exists('get_all_kendaraan')) {
    function get_all_kendaraan()
    {
        global $koneksi;

        $query = "
            SELECT k.*, u.nama_lengkap
            FROM tb_kendaraan k
            LEFT JOIN tb_user u ON k.id_user = u.id_user
            ORDER BY k.id_kendaraan DESC
        ";

        $result = mysqli_query($koneksi, $query);

        $kendaraans = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $kendaraans[] = $row;
        }

        return $kendaraans;
    }
}


// ===============================
// AMBIL KENDARAAN BERDASARKAN ID
// ===============================
if (!function_exists('get_kendaraan_by_id')) {
    function get_kendaraan_by_id($id_kendaraan)
    {
        global $koneksi;

        $id_kendaraan = mysqli_real_escape_string($koneksi, $id_kendaraan);

        $query = "
            SELECT k.*, u.nama_lengkap
            FROM tb_kendaraan k
            LEFT JOIN tb_user u ON k.id_user = u.id_user
            WHERE k.id_kendaraan = '$id_kendaraan'
        ";

        $result = mysqli_query($koneksi, $query);
        return mysqli_fetch_assoc($result);
    }
}



// ===============================
// PROSES TAMBAH KENDARAAN
// ===============================
if (isset($_POST['tambah_kendaraan'])) {
    global $koneksi;

    $plat_nomor      = mysqli_real_escape_string($koneksi, $_POST['plat_nomor']);
    $jenis_kendaraan = mysqli_real_escape_string($koneksi, $_POST['jenis_kendaraan']);
    $warna           = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $pemilik         = mysqli_real_escape_string($koneksi, $_POST['pemilik']);

    $id_user = $_SESSION['id_user'] ?? null;

    if ($plat_nomor == '' || $jenis_kendaraan == '' || $id_user == null) {
        $_SESSION['error'] = 'Data wajib belum lengkap!';
        header("Location: index.php#kendaraan");
        exit;
    }

    // Cek plat nomor
    $cek = mysqli_query($koneksi, "
        SELECT id_kendaraan 
        FROM tb_kendaraan 
        WHERE plat_nomor = '$plat_nomor'
    ");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Plat nomor sudah terdaftar!';
        header("Location: index.php#kendaraan");
        exit;
    }

    // INSERT TANPA kode_member
    mysqli_query($koneksi, "
        INSERT INTO tb_kendaraan 
        (plat_nomor, jenis_kendaraan, warna, pemilik, id_user)
        VALUES
        ('$plat_nomor', '$jenis_kendaraan', '$warna', '$pemilik', '$id_user')
    ");

    // Ambil ID kendaraan
    $id_kendaraan = mysqli_insert_id($koneksi);

    // Generate kode_member OPSI 2 (MBR00001)
    $kode_member = 'MBR' . str_pad($id_kendaraan, 5, '0', STR_PAD_LEFT);

    // Update kode_member
    mysqli_query($koneksi, "
        UPDATE tb_kendaraan
        SET kode_member = '$kode_member'
        WHERE id_kendaraan = '$id_kendaraan'
    ");

    // ✅ LOG AKTIVITAS (BENAR)
    log_aktivitas("Menambahkan kendaraan $plat_nomor dengan kode member $kode_member");

    $_SESSION['success'] = 'Data kendaraan berhasil ditambahkan';
    header("Location: index.php#kendaraan");
    exit;
}




// ===============================
// PROSES UPDATE KENDARAAN
// ===============================
if (isset($_POST['update_kendaraan'])) {
    global $koneksi;

    $id_kendaraan    = mysqli_real_escape_string($koneksi, $_POST['id_kendaraan']);
    $plat_nomor      = mysqli_real_escape_string($koneksi, $_POST['plat_nomor']);
    $jenis_kendaraan = mysqli_real_escape_string($koneksi, $_POST['jenis_kendaraan']);
    $warna           = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $pemilik         = mysqli_real_escape_string($koneksi, $_POST['pemilik']);

    if ($id_kendaraan == '' || $plat_nomor == '' || $jenis_kendaraan == '') {
        $_SESSION['error'] = 'Data wajib tidak boleh kosong!';
        header("Location: index.php#kendaraan");
        exit;
    }

    $cek = mysqli_query($koneksi, "
        SELECT id_kendaraan 
        FROM tb_kendaraan 
        WHERE plat_nomor = '$plat_nomor'
        AND id_kendaraan != '$id_kendaraan'
    ");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Plat nomor sudah digunakan kendaraan lain!';
        header("Location: index.php#kendaraan");
        exit;
    }

    query_log("
        UPDATE tb_kendaraan
        SET plat_nomor      = '$plat_nomor',
            jenis_kendaraan = '$jenis_kendaraan',
            warna           = '$warna',
            pemilik         = '$pemilik'
        WHERE id_kendaraan = '$id_kendaraan'
    ", "Mengubah kendaraan ID $id_kendaraan (Plat: $plat_nomor)");

    $_SESSION['success'] = 'Data kendaraan berhasil diperbarui';
    header("Location: index.php#kendaraan");
    exit;
}



// ===============================
// PROSES HAPUS KENDARAAN
// ===============================
if (isset($_POST['hapus_kendaraan'])) {
    global $koneksi;

    $id_kendaraan = mysqli_real_escape_string($koneksi, $_POST['id_kendaraan']);

    if ($id_kendaraan == '') {
        $_SESSION['error'] = 'ID kendaraan tidak valid!';
        header("Location: index.php#kendaraan");
        exit;
    }

    $cek = mysqli_query($koneksi, "
        SELECT id_kendaraan 
        FROM tb_transaksi 
        WHERE id_kendaraan = '$id_kendaraan'
        AND status = 'masuk'
    ");

    if ($cek && mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Kendaraan tidak bisa dihapus karena sedang parkir!';
        header("Location: index.php#kendaraan");
        exit;
    }

    query_log("
        DELETE FROM tb_kendaraan
        WHERE id_kendaraan = '$id_kendaraan'
    ", "Menghapus kendaraan ID $id_kendaraan");

    $_SESSION['success'] = 'Data kendaraan berhasil dihapus';
    header("Location: index.php#kendaraan");
    exit;
}



// ===============================
// AMBIL LOG AKTIVITAS
// ===============================
if (!function_exists('get_log_aktivitas')) {
    function get_log_aktivitas()
    {
        global $koneksi;

        $query = "
            SELECT l.*, u.nama_lengkap
            FROM tb_log_aktivitas l
            LEFT JOIN tb_user u ON l.id_user = u.id_user
            ORDER BY l.waktu_aktivitas DESC
            LIMIT 20
        ";

        $result = mysqli_query($koneksi, $query);

        $logs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $logs[] = $row;
        }

        return $logs;
    }
}