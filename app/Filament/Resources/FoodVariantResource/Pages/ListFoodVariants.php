<?php

namespace App\Filament\Resources\FoodVariantResource\Pages;

use App\Filament\Resources\FoodVariantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodVariants extends ListRecords
{
    protected static string $resource = FoodVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
