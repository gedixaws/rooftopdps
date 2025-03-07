<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $product_count = Product::count();
        $order_count = Order::count();
        $omset = Order::sum('total_price');

        return [
            Stat::make('Product', $product_count),
            Stat::make('Order', $order_count),
            Stat::make('Omset', number_format($omset,0,",",".")),
        ];
    }
}
