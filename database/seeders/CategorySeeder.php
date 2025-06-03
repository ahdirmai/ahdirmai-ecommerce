<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //  Schema::create('categories', function (Blueprint $table) {
    //             $table->id();
    //             $table->string('name')->unique();
    //             $table->enum('type', ['digital', 'physical'])->default('physical');
    //             $table->timestamps();
    //         });

    // public function up(): void
    // {
    //     Schema::table('categories', function (Blueprint $table) {
    //         // Add the 'slug' column to the 'categories' table
    //         $table->string('slug')->unique()->after('name');
    //         $table->boolean('is_active')->default(true)->after('slug');
    //     });
    // }

    // Schema::table('categories', function (Blueprint $table) {
    //         $table->string('description')->nullable()->after('name');
    //         $table->string('icon')->nullable()->after('description');
    //     });
    public function run(): void
    {
        //
        // digital categories
        $categories = [
            [
                'name' => 'E-books',
                'slug' => 'e-books',
                'description' => 'Digital books available for download',
                'icon' => 'fa-book',
                'type' => 'digital',
                'is_active' => true
            ],
            [
                'name' => 'Online Courses',
                'slug' => 'online-courses',
                'description' => 'Courses available for online learning',
                'icon' => 'fa-graduation-cap',
                'type' => 'digital',
                'is_active' => true
            ],
            [
                'name' => 'Software & Apps',
                'slug' => 'software-apps',
                'description' => 'Digital software and applications',
                'icon' => 'fa-laptop',
                'type' => 'digital',
                'is_active' => true
            ],
            [
                'name' => 'Digital Art & Graphics',
                'slug' => 'digital-art-graphics',
                'type' => 'digital',
                'description' => 'Digital art and graphic designs',
                'icon' => 'fa-paint-brush',
                'is_active' => true
            ]
        ];
        // physical categories
        // $physicalCategories = [
        //     [
        //         'name' => 'Books',
        //         'slug' => 'books',
        //         'description' => 'Physical books available for purchase',
        //         'icon' => 'fa-book',
        //         'type' => 'physical',
        //         'is_active' => true
        //     ],
        //     [
        //         'name' => 'Clothing & Apparel',
        //         'slug' => 'clothing-apparel',
        //         'description' => 'Physical clothing and apparel items',
        //         'icon' => 'fa-tshirt',
        //         'type' => 'physical',
        //         'is_active' => true
        //     ],
        //     [
        //         'name' => 'Home & Kitchen',
        //         'slug' => 'home-kitchen',
        //         'description' => 'Physical home and kitchen products',
        //         'icon' => 'fa-home',
        //         'type' => 'physical',
        //         'is_active' => true
        //     ],
        //     [
        //         'name' => 'Health & Beauty',
        //         'slug' => 'health-beauty',
        //         'description' => 'Physical health and beauty products',
        //         'icon' => 'fa-heartbeat',
        //         'type' => 'physical',
        //         'is_active' => true
        //     ]
        // ];
        // Insert digital categories
        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
        // // Insert physical categories
        // foreach ($physicalCategories as $category) {
        //     \App\Models\Category::create($category);
        // }
    }
}
