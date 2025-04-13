<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Product;
use App\Models\OrderProduct;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use App\Filament\Resources\OrderResource;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getCreatedNotification(): ?Notification
    {

        $notification = Notification::make()
            ->title("Pesanan Baru!")
            ->body("Pesanan " . $this->record->transaction_id . " telah dibuat.")
            ->actions([
                Action::make('Lihat Pesanan')
                    ->url(url('/admin/orders/' . $this->record->id . '/edit'))
                    ->markAsRead()
            ])
            ->sendToDatabase(auth()->user());


        $this->dispatch('play-notification-sound');
        return $notification;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function afterCreate(): void
    {
        $orderProducts = OrderProduct::where("order_id", $this->record->id)->get();

        foreach ($orderProducts as $orderProduct) {
            $product = Product::find($orderProduct->product_id);
            if ($product) {
                $product->decrement('stock', $orderProduct->quantity);
            }
        }
    }
}
