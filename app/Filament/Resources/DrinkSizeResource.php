<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\DrinkSize;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\ManagementProducts;
use App\Filament\Resources\DrinkSizeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DrinkSizeResource\RelationManagers;
use Illuminate\Validation\Rule;

class DrinkSizeResource extends Resource
{
    protected static ?string $model = DrinkSize::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = ManagementProducts::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('drink_id')
                    ->relationship('drink', 'name')
                    ->required(),
                Forms\Components\TextInput::make('size')
                    ->required()
                    ->maxLength(50)
                    ->rules(function (callable $get, ?DrinkSize $record) {
                        return [
                            Rule::unique('drink_sizes', 'size')
                                ->where('drink_id', $get('drink_id'))
                                ->ignore($record?->id),
                        ];
                    }),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('drink.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('size')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('price')
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
            'index' => Pages\ListDrinkSizes::route('/'),
            'create' => Pages\CreateDrinkSize::route('/create'),
            'edit' => Pages\EditDrinkSize::route('/{record}/edit'),
        ];
    }
}
