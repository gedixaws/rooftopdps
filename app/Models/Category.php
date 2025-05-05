<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function drinks()
    {
        return $this->hasMany(Drink::class);
    }

}
