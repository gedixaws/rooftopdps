<?php

namespace App\Providers;

use Livewire\Livewire;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use App\Http\Livewire\NotificationSound;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerRenderHook('scripts.end', function () {
                return <<<'HTML'
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        function playNotificationSound() {
                            let audio = new Audio('../audio/notification.mp3');
                            audio.play();
                        }
    
                        // Tunggu Livewire ter-load sepenuhnya
                        document.addEventListener("livewire:load", () => {
                            // Mendengarkan event 'play-notification-sound' dari Livewire
                            window.addEventListener('play-notification-sound', function() {
                                console.log("Notification sound triggered!"); // Debugging
                                playNotificationSound();
                            });
                        });
                    });
                </script>
                HTML;
            });
        });
    }
    
}
