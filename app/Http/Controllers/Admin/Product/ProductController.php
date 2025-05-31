<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Logic to display a list of products
        $query = Product::query();

        // Search logic
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $products = $query->paginate(10);

        $data = [
            'products' => $products,
        ];
        return view('admin.pages.products.index', $data);
    }

    // create
    public function create()
    {
        $method = 'POST';
        $categories = \App\Models\Category::all();

        $categoryOptions = $categories->map(function ($category) {
            return [
                'value' => $category->id,
                'text' => $category->name,
                'product_type' => $category->type,
            ];
        })->values()->all();

        $data = [
            'method' => $method,
            'action' => route('admin.products.store'),
            'categoryOptions' => $categoryOptions,
            'categories' => $categories,

        ];
        // Logic to show the product creation form
        return view('admin.pages.products.form', $data);
    }
    // store
    public function store(Request $request)
    {

        // return $request->all();
        //         "name": "testing",
        // "description": "tesitng",
        // "product_type": "digital",
        // "category_id": "7",
        // "format": "pdf",
        // "file_size": "223",
        // "pricing_type": "basic",
        // "price": "5",
        // "stock": "1"
        // }
        // Logic to store a new product
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_type' => 'required|in:digital,physical',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'integer|min:0',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate multiple images
        ]);
        // return $validatedData;
        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $validatedData['name'],
                'slug' => \Str::slug($validatedData['name']),
                'description' => $validatedData['description'],
                'product_type' => $validatedData['product_type'],
                'price' => $validatedData['price'],
                'category_id' => $validatedData['category_id'],
                'stock' => $validatedData['stock'] ?? 0,
            ]);

            // Handle additional fields based on product type
            if ($product->product_type === 'digital') {
                $product->update([
                    'format' => $request->input('format'),
                    'file_size' => $request->input('file_size'),
                ]);
            }

            // handle key features
            if ($request->has_key_features == 'on') {
                if ($request->keyfeatures != null) {
                    foreach ($request->keyfeatures as $keyfeature) {
                        $product->details()->create([
                            'attribute_icon' => $keyfeature['icon'] ?? null,
                            'attribute_name' => $keyfeature['name'],
                            'attribute_value' => $keyfeature['description'],
                        ]);
                    }
                }
            }

            // handle multiple image using spatie media library
            if ($request->hasFile('gallery')) {
                $product->addMultipleMediaFromRequest(['gallery'])
                    ->each(function ($file) {
                        $file->toMediaCollection('product_images');
                    });
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
            // return 'x';
            return redirect()->back()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }


        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }
}
