<?php

namespace App\Filament\Resources\DrinkSizeResource\Pages;

use App\Filament\Resources\DrinkSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDrinkSizes extends ListRecords
{
    protected static string $resource = DrinkSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
