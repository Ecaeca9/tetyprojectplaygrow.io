function confirmDelete(materiId) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Materi ini akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "hapus_materi.php?materi_id=" + materiId;
        }
    });
}
