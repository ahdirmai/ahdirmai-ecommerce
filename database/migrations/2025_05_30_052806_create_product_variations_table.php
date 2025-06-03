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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('variation_name'); // e.g., "Size", "Color"
            $table->string('variation_value'); // e.g., "Large", "Red"
            $table->decimal('price', 10, 2)->nullable(); // Variation-specific price
            $table->integer('stock')->default(0); // Stock for this variation
            $table->boolean('is_active')->default(true); // Whether this variation is active
            $table->string('sku')->unique(); // Stock Keeping Unit for the variation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
