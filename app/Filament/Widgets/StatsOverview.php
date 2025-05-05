<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '120s';
    protected function getStats(): array
    {
        $product_count = Product::count();
        $order_count = Order::where('status', 'paid')
                    ->where('is_active', 1)
                    ->count();
        $omset = Order::where('status', 'paid')->sum('total_price');

        return [
            Stat::make('Product', $product_count),
            Stat::make('Order', $order_count),
            Stat::make('Omset', number_format($omset,0,",",".")),
        ];
    }
}
