<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\AddOn;
use App\Models\Product;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Products;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Food;
use App\Models\Drink;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $cluster = Products::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\Select::make('food_id')
                            ->label('Food')
                            ->options(function () {
                                $usedFoodIds = Product::whereNotNull('food_id')->pluck('food_id')->toArray();
                                return Food::whereNotIn('id', $usedFoodIds)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->reactive()
                            ->unique(ignoreRecord: true) // Hindari duplikasi food_id
                            ->afterStateUpdated(fn($set) => $set('drink_id', null))
                            ->requiredWithout('drink_id'), // Wajib jika drink_id kosong

                        Forms\Components\Select::make('drink_id')
                            ->label('Drink')
                            ->options(function () {
                                $usedDrinkIds = Product::whereNotNull('drink_id')->pluck('drink_id')->toArray();
                                return Drink::whereNotIn('id', $usedDrinkIds)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->unique(ignoreRecord: true) // Hindari duplikasi drink_id
                            ->afterStateUpdated(fn($set) => $set('food_id', null)) // Reset food jika memilih drink
                            ->disabled(fn($get) => $get('food_id') !== null) // Disable jika food dipilih
                            ->requiredWithout('food_id'), // Wajib jika food_id kosong

                        Forms\Components\TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()
                            ->minValue(0)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktifkan Produk')
                            ->default(true),

                        Forms\Components\Placeholder::make('warning')
                            ->content('Harap pilih salah satu: Food atau Drink!')
                            ->hidden(fn($get) => $get('food_id') || $get('drink_id'))
                    ]),

                Forms\Components\Section::make('Gambar Produk')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Upload Gambar')
                            ->image()
                            ->directory('products')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->getStateUsing(fn($record) => $record->food?->name ?? $record->drink?->name ?? '-')
                    ->sortable()
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('food', fn($q) => $q->where('name', 'like', "%{$search}%"))
                              ->orWhereHas('drink', fn($q) => $q->where('name', 'like', "%{$search}%"));
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->getStateUsing(fn($record) => $record->food?->price ?? $record->drink?->price ?? '-')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->label('Hanya Produk Aktif')
                    ->query(fn($query) => $query->where('is_active', true)),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
