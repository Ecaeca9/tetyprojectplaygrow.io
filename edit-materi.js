// Fungsi untuk menangani modal konfirmasi
document.addEventListener('DOMContentLoaded', function() {
    const successModal = document.getElementById('successModal');
    
    // Tutup modal saat diklik
    if (successModal) {
        successModal.addEventListener('click', function() {
            const modalInstance = bootstrap.Modal.getInstance(successModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });

        // Tutup modal otomatis setelah 5 detik
        const autoCloseTimeout = setTimeout(function() {
            const modalInstance = bootstrap.Modal.getInstance(successModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }, 5000);

        // Membersihkan timeout jika modal ditutup sebelum waktunya
        successModal.addEventListener('hidden.bs.modal', function() {
            clearTimeout(autoCloseTimeout);
        });
    }
});