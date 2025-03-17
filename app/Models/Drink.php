<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(DrinkSize::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function getPriceAttribute()
    {
        // Jika minuman punya ukuran, ambil harga ukuran termurah
        if ($this->sizes()->exists()) {
            return $this->sizes()->orderBy('price', 'asc')->value('price') ?? $this->attributes['price'];
        }

        // Jika tidak ada ukuran, ambil harga dari tabel drinks
        return $this->attributes['price'] ?? null;
    }
}
