<div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>


    <div wire:poll.10s="checkPing">
        <script>
            document.addEventListener('successPaid', function() {

                var audio = new Audio('../../../audio/notification.mp3');
                audio.play();

                const notyf = new Notyf({
                    duration: 10000,
                    position: {
                        x: 'right',
                        y: 'top',
                    },
                    types: [{
                        type: 'success',
                        background: '#1f2937',
                        icon: {
                            className: 'custom-bell-icon',
                            tagName: 'span',
                            text: '🔔', 
                        }
                    }]
                });

                notyf.success('Pesanan Baru Masuk!');
            });
        </script>
    </div>
</div>
