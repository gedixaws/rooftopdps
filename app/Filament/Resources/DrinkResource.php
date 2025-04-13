<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Drink;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\ManagementProducts;
use App\Filament\Resources\DrinkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DrinkResource\RelationManagers;

class DrinkResource extends Resource
{
    protected static ?string $model = Drink::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = ManagementProducts::class;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->rules(fn ($record) => [
                        Rule::unique('drinks', 'name')->ignore($record?->id),
                    ]),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->nullable() // untuk tidak ada ukuran
                    ->required()
                    ->visible(fn($record) => !$record || !$record->sizes()->exists()), //tampil jika tidak ada ukuran
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price') // Harga otomatis dari getPriceAttribute()
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDrinks::route('/'),
            'create' => Pages\CreateDrink::route('/create'),
            'edit' => Pages\EditDrink::route('/{record}/edit'),
        ];
    }
}
