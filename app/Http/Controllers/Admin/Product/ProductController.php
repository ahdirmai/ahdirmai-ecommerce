<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $query = Product::orderBy('created_at', 'desc');

        if ($request->search) {
            $searchInput = $request->search;
            $query->where(function ($q) use ($searchInput) {
                $q->where('name', 'LIKE', "%{$searchInput}%")
                    ->orWhere('description', 'LIKE', "%{$searchInput}%");
            });
        }

        $products = $query->paginate(10)->withQueryString();
        return view('admin.pages.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('admin.pages.products.form', $this->getFormData());
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        return $this->withTransaction(function () use ($validated, $request) {
            $product = Product::create($this->getProductData($validated));

            $this->handleDigitalFields($product, $request);
            $this->handleKeyFeatures($product, $request);
            $this->handleGallery($product, $request);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully!');
        }, 'Failed to create product.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load(['details', 'media']);

        return view('admin.pages.products.form', array_merge(
            $this->getFormData('PUT', route('admin.products.update', $product->id)),
            compact('product')
        ));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateRequest($request, $product->id);

        return $this->withTransaction(function () use ($validated, $request, $product) {
            $product->update($this->getProductData($validated));

            $this->handleDigitalFields($product, $request);
            $this->handleKeyFeaturesUpdate($product, $request);
            $this->handleGalleryUpdate($product, $request);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully!');
        }, 'Failed to update product.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        return $this->withTransaction(function () use ($product) {
            $productName = $product->name;

            $product->clearMediaCollection('product_images');
            $product->details()->delete();
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', "Product '{$productName}' deleted successfully!");
        }, 'Failed to delete product.');
    }

    /**
     * Get products by category (for API or AJAX calls).
     */
    public function getByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        if (!$categoryId || !Category::where('id', $categoryId)->exists()) {
            return response()->json(['error' => 'Invalid category'], 400);
        }

        $products = Product::where('category_id', $categoryId)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'price']);

        return response()->json($products);
    }

    /**
     * Search products (for API or AJAX calls).
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'product_type', 'price']);

        return response()->json($products);
    }

    /**
     * DRY: Get form data for create/edit views.
     */
    private function getFormData(string $method = 'POST', string $action = null): array
    {
        $categories = Category::orderBy('name')->get();

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

    /**
     * DRY: Validate product request.
     */
    private function validateRequest(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('products', 'name')->ignore($ignoreId)
            ],
            'description' => 'nullable|string|max:1000',
            'product_type' => [
                'required',
                'string',
                'in:digital,physical'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99'
            ],
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'integer|min:0|max:999999',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Product name is required.',
            'name.min' => 'Product name must be at least 2 characters.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'name.unique' => 'This product name already exists.',
            'product_type.required' => 'Product type is required.',
            'product_type.in' => 'Product type must be either digital or physical.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'stock.integer' => 'Stock must be a valid integer.',
            'stock.min' => 'Stock cannot be negative.',
            'stock.max' => 'Stock cannot exceed 999,999.',
            'category_id.exists' => 'Selected category does not exist.',
            'gallery.*.image' => 'Gallery files must be images.',
            'gallery.*.mimes' => 'Gallery images must be jpeg, png, jpg, gif, or svg.',
            'gallery.*.max' => 'Gallery images cannot exceed 2MB.',
        ]);
    }

    /**
     * DRY: Get product data array for create/update.
     */
    private function getProductData(array $validated): array
    {
        return [
            'name' => trim($validated['name']),
            'slug' => $this->generateSlug($validated['name']),
            'description' => $validated['description'],
            'product_type' => $validated['product_type'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'stock' => $validated['stock'] ?? 0,
        ];
    }

    /**
     * Generate a unique slug from the product name.
     */
    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Handle digital product specific fields.
     */
    private function handleDigitalFields(Product $product, Request $request): void
    {
        if ($product->product_type === 'digital') {
            $product->update([
                'format' => $request->input('format'),
                'file_size' => $request->input('file_size'),
            ]);
        }
    }

    /**
     * Handle key features for new products.
     */
    private function handleKeyFeatures(Product $product, Request $request): void
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

    /**
     * Handle key features update for existing products.
     */
    private function handleKeyFeaturesUpdate(Product $product, Request $request): void
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

    /**
     * Handle gallery upload for new products.
     */
    private function handleGallery(Product $product, Request $request): void
    {
        if ($request->hasFile('gallery')) {
            $product->addMultipleMediaFromRequest(['gallery'])
                ->each(fn($file) => $file->toMediaCollection('product_images'));
        }
    }

    /**
     * Handle gallery update for existing products.
     */
    private function handleGalleryUpdate(Product $product, Request $request): void
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

    /**
     * DRY: Handle DB transactions and exceptions.
     */
    private function withTransaction(callable $callback, string $defaultErrorMessage): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $response = $callback();
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();

            $errorMessage = $defaultErrorMessage;
            if (!app()->environment('production')) {
                $errorMessage .= ' Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }
}
