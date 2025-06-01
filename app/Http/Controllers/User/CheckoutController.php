<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        // return $request->all();
        // validated $selecteditems = required,array
        $request->validate([
            'selected_items' => 'required|array',
            'cart' => 'required',
        ]);

        // check cart->user_id == auth()->user()->id
        $cart = Cart::find($request->cart);
        if (!$cart || $cart->user_id != auth()->user()->id) {
            Alert::error('Error', 'Cart not found or does not belong to you!');
            return redirect()->route('user.cart.index');
        }

        // check if selected_items are in cart items
        $selectedItems = $request->input('selected_items');
        $cartItems = $cart->items->pluck('id')->toArray();
        foreach ($selectedItems as $itemId) {
            if (!in_array($itemId, $cartItems)) {
                Alert::error('Error', 'Some selected items are not in your cart!');
                return redirect()->route('user.cart.index');
            }
        }

        $selectedItems = $cart->items()->whereIn('id', $selectedItems)->get();

        $data = [
            'cart' => $cart,
            'selectedItems' => $selectedItems,
            'totalPrice' => $selectedItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            }),
            'totalQuantity' => $selectedItems->sum('quantity'),
        ];


        return view('user.checkout.index', $data);
    }
}
