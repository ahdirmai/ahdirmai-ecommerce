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
        //
        // add colum format, filezie

        Schema::table('products', function (Blueprint $table) {
            $table->string('format')->nullable()->after('product_type');
            $table->string('file_size')->nullable()->after('format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['format', 'file_size']);
        });
    }
};
