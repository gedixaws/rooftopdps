<div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
        // Inisialisasi Notyf
        const notyf = new Notyf({
            duration: 10000,
            position: {
                x: 'right',
                y: 'top',
            },
            types: [{
                type: 'stockWarning',
                background: '#1f2937',
                icon: {
                    className: 'custom-bell-icon',
                    tagName: 'span',
                    text: '🔔',
                }
            }]
        });

        window.addEventListener('show-toast', event => {
            notyf.open({
                type: 'stockWarning',
                message: event.detail.message || 'Stok hampir habis!',
            });
        });

        // Tetap gunakan polling Livewire
        setInterval(() => {
            @this.checkStock();
        }, 10000); // Setiap 10 detik
    </script>
</div>
