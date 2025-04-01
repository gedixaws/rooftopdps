<div wire:poll.5s="checkNotifications"></div>

<script>
    window.addEventListener('play-notification-sound', function () {
        var audio = new Audio('../audio/notification.mp3');
        audio.play();
    });
</script>
