<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'food_id',
        'drink_id',
        'stock',
        'image',
        'is_active',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function drink()
    {
        return $this->belongsTo(Drink::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function getProductNameAttribute()
    {
        return $this->food?->name ?? $this->drink?->name ?? 'Tidak diketahui';
    }

    public function foodVariants()
    {
        return $this->food ? $this->food->variants() : null;
    }

    public function drinkSizes()
    {
        return $this->drink ? $this->drink->sizes() : null;
    }

    
}
