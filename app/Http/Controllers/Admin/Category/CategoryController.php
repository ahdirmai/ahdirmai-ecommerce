<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $query = Category::orderBy('created_at', 'desc');

        if ($request->search) {
            $searchInput = $request->search;
            $query->where(function ($q) use ($searchInput) {
                $q->where('name', 'LIKE', "%{$searchInput}%")
                    ->orWhere('slug', 'LIKE', "%{$searchInput}%");
            });
        }

        $categories = $query->paginate(10)->withQueryString();
        return view('admin.pages.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        return $this->withTransaction(function () use ($validated) {
            Category::create([
                'name' => trim($validated['name']),
                'type' => $validated['type'],
                'slug' => $this->generateSlug($validated['name']),
                'description' => $validated['description'],
                'icon' => $validated['icon']

            ]);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category created successfully!');
        }, 'Failed to create category.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        $categories = Category::orderBy('created_at', 'desc')->get();

        return view('admin.pages.categories.index', compact('categories', 'category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        // return 'x';
        $validated = $this->validateRequest($request, $category->id);

        return $this->withTransaction(function () use ($category, $validated) {
            $category->update([
                'name' => trim($validated['name']),
                'type' => $validated['type'],
                'slug' => $this->generateSlug($validated['name']),
                'description' => $validated['description'],
                'icon' => $validated['icon']
            ]);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');
        }, 'Failed to update category.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        return $this->withTransaction(function () use ($category) {
            $categoryName = $category->name;
            $category->delete();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', "Category '{$categoryName}' deleted successfully!");
        }, 'Failed to delete category.');
    }

    /**
     * Generate a unique slug from the category name.
     */
    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $originalSlug = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Get categories by type (for API or AJAX calls).
     */
    public function getByType(Request $request)
    {
        $type = $request->get('type');

        if (!in_array($type, ['digital', 'physical'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $categories = Category::where('type', $type)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json($categories);
    }

    /**
     * Search categories (for API or AJAX calls).
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'type']);

        return response()->json($categories);
    }

    /**
     * DRY: Validate category request.
     */
    private function validateRequest(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                Rule::unique('categories', 'name')->ignore($ignoreId)
            ],
            'type' => [
                'required',
                'string',
                'in:digital,physical'
            ],
            'description' => 'required|string|max:255',
            'icon' => 'required|string|max:100'
        ], [
            'name.required' => 'Category name is required.',
            'name.min' => 'Category name must be at least 2 characters.',
            'name.max' => 'Category name cannot exceed 100 characters.',
            'name.unique' => 'This category name already exists.',
            'type.required' => 'Category type is required.',
            'type.in' => 'Category type must be either digital or physical.',
            'description.max' => 'Description cannot exceed 255 characters.',
            'icon.max' => 'Icon cannot exceed 100 characters.',
            'description.required' => 'Description is required.',
            'icon.required' => 'Icon is required.'

        ]);
    }

    /**
     * DRY: Handle DB transactions and exception.
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
