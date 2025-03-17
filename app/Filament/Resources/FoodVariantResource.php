<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FoodVariant;
use Filament\Resources\Resource;
use App\Filament\Clusters\ManagementProducts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FoodVariantResource\Pages;
use App\Filament\Resources\FoodVariantResource\RelationManagers;

class FoodVariantResource extends Resource
{
    protected static ?string $model = FoodVariant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = ManagementProducts::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('food_id')
                    ->relationship('food', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('food.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('price')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFoodVariants::route('/'),
            'create' => Pages\CreateFoodVariant::route('/create'),
            'edit' => Pages\EditFoodVariant::route('/{record}/edit'),
        ];
    }
}
