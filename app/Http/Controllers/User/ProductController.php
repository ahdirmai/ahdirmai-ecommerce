<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::all()->load(['category', 'media']);
        // return 'all product';
        // Here you would typically fetch products from the database
        // For example:
        // $products = Product::all();

        $data = [
            'products' => $products
        ];
        // For now, we'll return a simple view
        return view('user.product.index', $data);
    }

    public function show($slug)
    {
        // Find the product by slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Load related models
        // Assuming you have relationships defined in your Product model
        // like category, media, and details
        $product = $product->load(['category', 'media', 'details']);

        $data = [
            'product' => $product
        ];
        return view('user.product.detail', $data);
    }
}
