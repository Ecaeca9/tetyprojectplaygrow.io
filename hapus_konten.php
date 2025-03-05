<?php
session_start();
include 'controller.php';

// Cek apakah pengajar sudah login
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'pengajar') {
    header('Location: login.php');
    exit();
}

// Mendapatkan konten_id dan materi_id dari URL
$konten_id = isset($_GET['konten_id']) ? $_GET['konten_id'] : 0;
$materi_id = isset($_GET['materi_id']) ? $_GET['materi_id'] : 0;

// Memanggil fungsi hapusKonten untuk menghapus konten
if (hapusKonten($konten_id, $materi_id)) {
    // Gunakan SweetAlert untuk konfirmasi berhasil
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Konten berhasil dihapus.',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'materi_detail.php?materi_id=$materi_id';
        }
    });
    </script>";
} else {
    // Gunakan SweetAlert untuk konfirmasi gagal
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        title: 'Gagal!',
        text: 'Tidak dapat menghapus konten.',
        icon: 'error',
        confirmButtonText: 'Kembali'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'materi_detail.php?materi_id=$materi_id';
        }
    });
    </script>";
}
?>