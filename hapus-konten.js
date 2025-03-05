$(document).ready(function() {
    $('.hapus-konten').on('click', function(e) {
        e.preventDefault(); // Prevent default link behavior

        // Get konten_id and materi_id from data attributes
        var kontenId = $(this).data('konten-id');
        var materiId = $(this).data('materi-id');

        // Use SweetAlert for confirmation
        Swal.fire({
            title: 'Konfirmasi Hapus Konten',
            text: 'Apakah Anda yakin ingin menghapus konten ini? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to delete script with konten_id and materi_id
                window.location.href = 'hapus_konten.php?konten_id=' + kontenId + '&materi_id=' + materiId;
            }
        });
    });
});