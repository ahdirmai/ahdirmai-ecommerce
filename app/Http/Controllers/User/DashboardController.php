<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::latest()->take(5)->get();

        $categories = Category::all();

        $data = [
            'products' => $products,
            'categories' => $categories,
        ];
        return view('user.dashboard.index', $data);
    }
}
