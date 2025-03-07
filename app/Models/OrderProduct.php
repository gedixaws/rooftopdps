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
        'product_id',
        'quantity',
        'serving_type',
        'unit_price',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}