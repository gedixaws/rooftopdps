<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrinkSize extends Model
{
    protected $fillable = [
        'drink_id',
        'size',
        'price'
    ];

    public function drink()
    {
        return $this->belongsTo(Drink::class);
    }
}
