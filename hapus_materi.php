<?php
session_start();
include 'controller.php';  // Memasukkan controller untuk mengedit dan menghapus materi

// Cek apakah pengajar sudah login
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'pengajar') {
    header('Location: login.php');
    exit();
}

// Periksa apakah `materi_id` valid
if (!isset($_GET['materi_id']) || !is_numeric($_GET['materi_id'])) {
    echo "<script>
        alert('ID materi tidak valid!');
        window.location.href = 'dashboard_pengajar.php';
    </script>";
    exit();
}

$materi_id = intval($_GET['materi_id']); // Pastikan ID adalah angka

// Memanggil fungsi hapusMateri untuk menghapus materi
if (hapusMateri($materi_id)) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            title: 'Materi Terhapus!',
            text: 'Materi berhasil dihapus dari sistem.',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location='dashboard_pengajar.php';
        });
    </script>";
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            title: 'Gagal Menghapus!',
            text: 'Terjadi kesalahan saat menghapus materi. Coba lagi nanti.',
            icon: 'error',
            confirmButtonText: 'Kembali'
        }).then(() => {
            window.location='dashboard_pengajar.php';
        });
    </script>";
}
?>
