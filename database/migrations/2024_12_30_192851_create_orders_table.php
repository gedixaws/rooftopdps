<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 5)->unique();
            $table->string('slug')->unique();
            $table->decimal('total_price', 10, 2);
            $table->string('name');
            $table->text('note')->nullable();
            $table->foreignId('payment_method_id')
                ->nullable()
                ->constrained('payment_methods')
                ->nullOnDelete();
            $table->double('paid_amount')->nullable();
            $table->double('change_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
