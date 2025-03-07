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
        'name',
        'slug',
        'description',
        'stock',
        'price',
        'category_id',
        'image',
        'is_active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }

    public function scopeSearch($query, $value)
    {
        $query->where("name", "like", "%{$value}%");
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
}
