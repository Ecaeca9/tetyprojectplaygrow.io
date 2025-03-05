document.addEventListener("DOMContentLoaded", function () {
    // Fungsi konfirmasi hapus user
    window.konfirmasiHapus = function (id) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "User ini akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "dashboard_pengajar.php?hapus_id=" + id;
            }
        });
    }
});
