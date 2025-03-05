document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit Konten Page Loaded');

    // Tampilkan/sembunyikan input berdasarkan tipe konten
    const typeSelect = document.getElementById('type');
    const textContent = document.getElementById('textContent');
    const videoContent = document.getElementById('videoContent');

    function toggleContentFields() {
        if (typeSelect.value === 'description') {
            textContent.style.display = 'block';
            videoContent.style.display = 'none';
        } else {
            textContent.style.display = 'none';
            videoContent.style.display = 'block';
        }
    }

    // Inisialisasi tampilan awal
    toggleContentFields();

    // Tambahkan event listener untuk perubahan tipe
    typeSelect.addEventListener('change', toggleContentFields);

    // Penanganan pesan sukses/error
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    // Debug logging
    console.log('Success Message Element:', successMessage);
    console.log('Success Message Value:', successMessage ? successMessage.value : 'Tidak ada');
    console.log('Error Message Element:', errorMessage);
    console.log('Error Message Value:', errorMessage ? errorMessage.value : 'Tidak ada');

    // Tampilkan pesan sukses
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage.value,
            confirmButtonText: 'OK'
        }).then(() => {
            // Hapus pesan sukses dari session
            successMessage.remove();
        });
    }

    // Tampilkan pesan error
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: errorMessage.value,
            confirmButtonText: 'Tutup'
        }).then(() => {
            // Hapus pesan error dari session
            errorMessage.remove();
        });
    }
});