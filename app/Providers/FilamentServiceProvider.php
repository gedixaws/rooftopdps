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
        Filament::registerRenderHook('scripts.end', function () {
            return <<<'HTML'
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    function playNotificationSound() {
                        let audio = new Audio('/audio/notification.mp3');
                        audio.play();
                    }

                    // Cek apakah event notify dipanggil
                    Livewire.on('notify', () => {
                        console.log("Livewire notify event triggered!"); // Debugging
                        playNotificationSound();
                    });
                });
            </script>
            HTML;
        });
    }
}
