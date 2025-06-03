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
        Schema::table('categories', function (Blueprint $table) {
            // Add the 'slug' column to the 'categories' table
            $table->string('slug')->unique()->after('name');
            $table->boolean('is_active')->default(true)->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('categories', function (Blueprint $table) {
            // Drop the 'slug' and 'is_active' columns from the 'categories' table
            $table->dropColumn(['slug', 'is_active']);
        });
    }
};
