<?php

namespace App\Models;

use App\Observers\OrderProductObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([OrderProductObserver::class])]
class OrderProduct extends Model
{

    use HasFactory;
    protected $fillable = [
        'order_id',
        'food_variant_id',
        'drink_size_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function foodVariant()
    {
        return $this->belongsTo(FoodVariant::class);
    }

    public function drinkSize()
    {
        return $this->belongsTo(DrinkSize::class);
    }

}
