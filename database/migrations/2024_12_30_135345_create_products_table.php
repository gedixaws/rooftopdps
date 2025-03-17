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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')
                ->nullable()
                ->constrained('foods')
                ->onDelete('cascade');
            $table->foreignId('drink_id')
                ->nullable()
                ->constrained('drinks')
                ->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
