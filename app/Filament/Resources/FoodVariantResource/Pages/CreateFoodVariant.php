<?php

namespace App\Filament\Resources\FoodVariantResource\Pages;

use App\Filament\Resources\FoodVariantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFoodVariant extends CreateRecord
{
    protected static string $resource = FoodVariantResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
