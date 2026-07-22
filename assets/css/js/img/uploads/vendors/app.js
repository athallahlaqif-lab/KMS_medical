document.addEventListener("DOMContentLoaded", function () {
    console.log("KMS Medical System Loaded");

    // Re-initialize Bootstrap Tooltips & Popovers if any
    const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle SweetAlert2 or standard Delete Confirmations automatically if used
    const deleteButtons = document.querySelectorAll(".btn-delete, .delete-btn");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            if (typeof Swal !== 'undefined') {
                e.preventDefault();
                const targetUrl = this.getAttribute("href") || this.dataset.url;
                if (!targetUrl) return;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = targetUrl;
                    }
                });
            }
        });
    });
});