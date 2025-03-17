<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Filament\Notifications\Actions\Action;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return FilamentNotification::make()
            ->title('Pesanan Baru!')
            ->body('Pesanan #' . $this->order->id . ' telah dibuat.')
            ->actions([
                Action::make('Lihat Pesanan')
                    ->url(url('/admin/orders/' . $this->order->id . '/edit'))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage(); // ✅ Mengubah ke format yang bisa disimpan di database
    }
}
