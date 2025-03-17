<?php

namespace App\Filament\Resources\DrinkSizeResource\Pages;

use App\Filament\Resources\DrinkSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDrinkSize extends EditRecord
{
    protected static string $resource = DrinkSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
