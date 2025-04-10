<div>
    <script>
        document.addEventListener('play-notification-sound', function () {
      
            var audio = new Audio('../../../audio/notification.mp3');
            audio.play();
    
            setTimeout(function () {
                window.location.href = '/admin/orders'; 
            }, 1100);
        });
    </script>
</div>
