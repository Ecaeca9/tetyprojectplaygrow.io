// script_tambah_konten.js
document.addEventListener('DOMContentLoaded', function() {
    // Menampilkan form video atau teks berdasarkan pilihan
    const typeSelect = document.getElementById('type');
    const textContent = document.getElementById('textContent');
    const videoContent = document.getElementById('videoContent');

    typeSelect.addEventListener('change', function() {
        const type = this.value;
        if (type === 'description') {
            textContent.style.display = 'block';
            videoContent.style.display = 'none';
        } else if (type === 'video') {
            textContent.style.display = 'none';
            videoContent.style.display = 'block';
        }
    });

    // Tampilkan Sweet Alert untuk pesan sukses atau error
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('success') === 'true') {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Konten baru telah ditambahkan ke materi.',
            icon: 'success',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#3085d6',
            animation: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const materiId = urlParams.get('materi_id');
                window.location.href = `materi_detail.php?materi_id=${materiId}`;
            }
        });
    }

    if (urlParams.get('error') === 'true') {
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menambahkan konten.',
            icon: 'error',
            confirmButtonText: 'Coba Lagi',
            confirmButtonColor: '#d33',
            animation: true,
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    }
});