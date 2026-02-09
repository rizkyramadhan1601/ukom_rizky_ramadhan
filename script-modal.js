// Modal Edit User - PERBAIKI INI
document.addEventListener('DOMContentLoaded', function () {
    const modalEdit = document.getElementById('modalEditUser');
    
    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            document.getElementById('edit_id_user').value = button.getAttribute('data-id');
            document.getElementById('edit_nama').value = button.getAttribute('data-nama');
            document.getElementById('edit_username').value = button.getAttribute('data-username');
            document.getElementById('edit_role').value = button.getAttribute('data-role');
            document.getElementById('edit_status').value = button.getAttribute('data-status');
        });
    }
});

// Modal Hapus User
document.addEventListener('DOMContentLoaded', function () {
    const modalHapus = document.getElementById('modalHapusUser');
    
    if (modalHapus) {
        modalHapus.addEventListener('show.bs.modal', function (event) {
            document.getElementById('hapus_id_user').value = event.relatedTarget.dataset.id;
        });
    }
});

// BATAS SECTION

document.addEventListener('DOMContentLoaded', function () {
    var modalEditTarif = document.getElementById('modalEditTarif');

    if (modalEditTarif) {
        modalEditTarif.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id    = button.getAttribute('data-id');
            var tarif = button.getAttribute('data-tarif');

            document.getElementById('edit_id_tarif').value = id;
            document.getElementById('edit_tarif_per_jam').value = tarif;
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var modalHapusTarif = document.getElementById('modalHapusTarif');

    if (modalHapusTarif) {
        modalHapusTarif.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id    = button.getAttribute('data-id');
            var jenis = button.getAttribute('data-jenis');

            document.getElementById('hapus_id_tarif').value = id;
            document.getElementById('hapus_jenis_kendaraan').innerText = jenis;
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var modalEditArea = document.getElementById('modalEditArea');

    if (modalEditArea) {
        modalEditArea.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id        = button.getAttribute('data-id');
            var nama      = button.getAttribute('data-nama');
            var kapasitas = button.getAttribute('data-kapasitas');

            document.getElementById('edit_id_area').value   = id;
            document.getElementById('edit_nama_area').value = nama;
            document.getElementById('edit_kapasitas').value = kapasitas;
        });
    }
});

document.addEventListener('DOMContentLoading', function () {
    var modalHapusArea = document.getElementById('modalHapusArea');

    if (modalHapusArea) {
        modalHapusArea.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id   = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');

            document.getElementById('hapus_id_area').value = id;
            document.getElementById('hapus_nama_area').innerText = nama;
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var modalEditKendaraan = document.getElementById('modalEditKendaraan');

    if (modalEditKendaraan) {
        modalEditKendaraan.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            document.getElementById('edit_id_kendaraan').value   = button.getAttribute('data-id') || '';
            document.getElementById('edit_kode_member').value    = button.getAttribute('data-kode') || '';
            document.getElementById('edit_plat_nomor').value     = button.getAttribute('data-plat') || '';
            document.getElementById('edit_jenis_kendaraan').value= button.getAttribute('data-jenis') || '';
            document.getElementById('edit_warna').value          = button.getAttribute('data-warna') || '';
            document.getElementById('edit_pemilik').value        = button.getAttribute('data-pemilik') || '';
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var modalHapusKendaraan = document.getElementById('modalHapusKendaraan');

    if (modalHapusKendaraan) {
        modalHapusKendaraan.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id   = button.getAttribute('data-id');
            var plat = button.getAttribute('data-plat');

            document.getElementById('hapus_id_kendaraan').value = id;
            document.getElementById('hapus_plat_kendaraan').innerText = plat;
        });
    }
});