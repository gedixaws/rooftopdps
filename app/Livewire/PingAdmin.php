<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class PingAdmin extends Component
{
    public $lastPing;
    public $shownPing; // untuk melacak yang sudah ditampilkan
    public function mount()
    {
        $this->checkPing();
    }

    public function checkPing(): ?Notification
    {
        $ping = Cache::pull('last_user_ping');

        if ($ping) {

            $orderId = $ping['order_id'] ?? null;
            $transactionId = $ping['transaction_id'] ?? null;

            $notification = Notification::make()
                ->title("Pesanan Baru!")
                ->body("Pesanan {$transactionId} telah dibuat.")
                ->actions([
                    Action::make('Lihat Pesanan')
                        ->url(url('/admin/orders/' . $orderId . '/edit'))
                        ->markAsRead()
                ])
                ->sendToDatabase(auth()->user());


            $this->dispatch('successPaid');
            return $notification;
        }
        return null;
    }

    public function render()
    {
        return view('livewire.ping-admin');
    }
}
