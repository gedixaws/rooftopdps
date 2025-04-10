<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductFavorite extends BaseWidget
{

    protected static ?int $sort = 3;
    protected static ?string $heading = 'Produk Favorit';

    public function table(Table $table): Table
    {
        $productQuery = Product::query()
            ->withCount([
                'orderProducts as order_products_count' => function ($query) {
                    $query->whereHas('order', function ($q) {
                        $q->where('status', 'paid'); // Hanya hitung produk dari pesanan berstatus "paid"
                    });
                }
            ])
            ->orderByDesc('order_products_count')
            ->take(10);

        return $table
            ->query($productQuery)
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('food', fn($q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('drink', fn($q) => $q->where('name', 'like', "%{$search}%"));
                    }),
                Tables\Columns\TextColumn::make('order_products_count')
                    ->label('Dipesan')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5);
    }
}
