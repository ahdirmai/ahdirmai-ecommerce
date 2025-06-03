<?php

namespace App\View\Components;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        if (Auth::check()) {
            $cart_count = Cart::where('user_id', Auth::id())->first()->items->count('quantity');
        } else {
            $cart_count = 0;
        }
        return view('layouts.app', compact('cart_count'));
    }
}
