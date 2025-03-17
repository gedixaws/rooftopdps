<?php

namespace App\Filament\Resources\FoodVariantResource\Pages;

use App\Filament\Resources\FoodVariantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodVariant extends EditRecord
{
    protected static string $resource = FoodVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
