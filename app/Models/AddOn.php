<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class AddOn extends Model
{
    use HasFactory;
    protected $table = 'add_ons'; 

    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'stock', 
        'price', 
        'description', 
        'is_active'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class,'id', 'category_id');
    }

    public static function generateUniqueSlug(string $name): String
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (self::where ('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}