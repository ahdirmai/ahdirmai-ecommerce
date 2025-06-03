<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'media']);

        // Filter by categories (expects array of category slug)
        if ($request->filled('categories')) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // Filter by product_type (expects string or array)
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        // Handle sort
        if ($request->filled('sort')) {
            $sort = $request->sort;
            switch ($sort) {
                case 'newest':
                    $query->orderByDesc('created_at');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    // No sorting or fallback
                    break;
            }
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        $data = [
            'products' => $products,
            'categories' => $categories,
            'categoryOptions' => $categories->map(fn($category) => [
                'value' => $category->id,
                'slug' => $category->slug,
                'text' => $category->name,
                'product_type' => $category->type,
            ])->values()->all()
        ];

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
