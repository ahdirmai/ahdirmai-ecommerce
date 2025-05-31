<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('admin.pages.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.pages.products.form', $this->getFormData());
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateProduct($request);

        DB::transaction(function () use ($validatedData, $request) {
            $product = Product::create($this->getProductData($validatedData));

            $this->handleDigitalFields($product, $request);
            $this->handleKeyFeatures($product, $request);
            $this->handleGallery($product, $request);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['details', 'media']);

        return view('admin.pages.products.form', array_merge(
            $this->getFormData('PUT', route('admin.products.update', $product->id)),
            compact('product')
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $this->validateProduct($request);

        DB::transaction(function () use ($validatedData, $request, $product) {
            $product->update($this->getProductData($validatedData));

            $this->handleDigitalFields($product, $request);
            $this->handleKeyFeaturesUpdate($product, $request);
            $this->handleGalleryUpdate($product, $request);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $product->clearMediaCollection('product_images');
            $product->details()->delete();
            $product->delete();
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // Private helper methods
    private function getFormData($method = 'POST', $action = null)
    {
        $categories = Category::all();

        return [
            'method' => $method,
            'action' => $action ?: route('admin.products.store'),
            'categories' => $categories,
            'categoryOptions' => $categories->map(fn($category) => [
                'value' => $category->id,
                'text' => $category->name,
                'product_type' => $category->type,
            ])->values()->all(),
        ];
    }

    private function validateProduct(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_type' => 'required|in:digital,physical',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'integer|min:0',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function getProductData(array $validatedData)
    {
        return [
            'name' => $validatedData['name'],
            'slug' => \Str::slug($validatedData['name']),
            'description' => $validatedData['description'],
            'product_type' => $validatedData['product_type'],
            'price' => $validatedData['price'],
            'category_id' => $validatedData['category_id'],
            'stock' => $validatedData['stock'] ?? 0,
        ];
    }

    private function handleDigitalFields(Product $product, Request $request)
    {
        if ($product->product_type === 'digital') {
            $product->update([
                'format' => $request->input('format'),
                'file_size' => $request->input('file_size'),
            ]);
        }
    }

    private function handleKeyFeatures(Product $product, Request $request)
    {
        if ($request->has_key_features !== 'on' || !$request->keyfeatures) {
            return;
        }

        foreach ($request->keyfeatures as $keyfeature) {
            $product->details()->create([
                'attribute_icon' => $keyfeature['icon'] ?? null,
                'attribute_name' => $keyfeature['name'],
                'attribute_value' => $keyfeature['description'],
            ]);
        }
    }

    private function handleKeyFeaturesUpdate(Product $product, Request $request)
    {
        if ($request->has_key_features !== 'on') {
            $product->details()->delete();
            return;
        }

        if (!$request->keyfeatures) {
            return;
        }

        foreach ($request->keyfeatures as $keyfeature) {
            if (!empty($keyfeature['id'])) {
                $product->details()->find($keyfeature['id'])?->update([
                    'attribute_icon' => $keyfeature['icon'] ?? null,
                    'attribute_name' => $keyfeature['name'],
                    'attribute_value' => $keyfeature['description'],
                ]);
            } else {
                $product->details()->create([
                    'attribute_icon' => $keyfeature['icon'] ?? null,
                    'attribute_name' => $keyfeature['name'],
                    'attribute_value' => $keyfeature['description'],
                ]);
            }
        }
    }

    private function handleGallery(Product $product, Request $request)
    {
        if ($request->hasFile('gallery')) {
            $product->addMultipleMediaFromRequest(['gallery'])
                ->each(fn($file) => $file->toMediaCollection('product_images'));
        }
    }

    private function handleGalleryUpdate(Product $product, Request $request)
    {
        // Remove images not in existing_gallery
        if ($request->has('existing_gallery') && is_array($request->existing_gallery)) {
            $product->media()->each(function ($media) use ($request) {
                if (!in_array($media->id, $request->existing_gallery)) {
                    $media->delete();
                }
            });
        } else {
            $product->clearMediaCollection('product_images');
        }

        $this->handleGallery($product, $request);
    }
}
