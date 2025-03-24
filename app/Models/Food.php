<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'category_id',
        'name',
        'price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(FoodVariant::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function getPriceAttribute()
    {
        // Jika punya variant, ambil harga dari variant termurah
        if ($this->variants()->exists()) {
            return $this->variants()->orderBy('price', 'asc')->value('price');
        }

        // Ambil harga langsung dari food
        return $this->attributes['price'] ?? 0;
    }
}
