<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductAlert extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Produk Hampir Habis';
    protected static ?string $pollingInterval = '120s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('stock', '<=', 10)->orderBy('stock', 'asc')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('food', fn($q) => $q->where('name', 'like', "%{$search}%"))
                                     ->orWhereHas('drink', fn($q) => $q->where('name', 'like', "%{$search}%"));
                    }),
                Tables\Columns\BadgeColumn::make('stock')
                    ->label('stok')
                    ->numeric()
                    ->color(static function ($state): string {
                        if ($state < 5) {
                            return 'danger';
                        } elseif ($state <= 10) {
                            return 'warning';
                        }
                    })
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5);
    }
}
