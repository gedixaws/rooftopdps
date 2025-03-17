<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'total_price',
        'slug',
        'name',
        'note',
        'payment_method_id',
        'paid_amount',
        'change_amount',
    ];

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public static function generateTransactionId(): string
    {
        do {
            $letter = chr(rand(65, 90)); // Huruf A-Z
            $number = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // 3 digit angka
            $transactionId = "#{$letter}{$number}";
        } while (self::where('transaction_id', $transactionId)->exists()); // Supaya tidak duplikat

        return $transactionId;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->transaction_id) {
                $order->transaction_id = self::generateTransactionId();
            }

            $order->slug = Str::slug($order->transaction_id, '-');
        });
    }
}
