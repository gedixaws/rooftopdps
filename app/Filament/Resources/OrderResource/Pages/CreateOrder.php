<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use App\Filament\Resources\OrderResource;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    public function afterCreate(): void
    {
        
        Notification::make()
            ->title("Pesanan Baru!")
            ->body("Pesanan " . $this->record->transaction_id . " telah dibuat.")
            ->actions([
                Action::make('Lihat Pesanan')
                    ->url(url('/admin/orders/' . $this->record->id . '/edit'))
                    ->markAsRead()
            ])
            ->sendToDatabase(auth()->user());
            $this->dispatch('notify');
    }
}
