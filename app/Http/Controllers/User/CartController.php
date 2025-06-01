<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class CartController extends Controller
{

    public function index()
    {
        return 'cart index';
    }

    public function getModalCart()
    {
        $user_cart = Cart::where('user_id', Auth::user()->id)->first()->load('items.product');


        $data = [
            'cartItems' => $user_cart->items,
        ];

        // Logic to retrieve cart items and return a view or JSON response
        return view('user.cart.modal_cart', $data); // Assuming you have a view for the modal cart
    }
    public function addToCart(Request $request)
    {

        try {
            \DB::beginTransaction();

            $productId = $request->input('product_id');
            $product = Product::find($productId);
            if (!$product) {
                Alert::error('Error', 'Product not found!');
                return redirect()->back();
            }

            $user_cart = Cart::where('user_id', Auth::user()->id)->first();
            if (!$user_cart) {
                $user_cart = Cart::create(['user_id' => Auth::user()->id]);
            }

            // Check if the item already exists in the cart

            $cartItem = $user_cart->items()->where('product_id', $product->id)->first();
            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                $user_cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => 1,
                    // 'total_price' => $product->price, // Adjust as needed
                ]);
            }

            \DB::commit();

            Alert::success('Success', 'Item added to cart successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollBack();
            if (app()->environment('local')) {
                # code...
                Alert::error('Error', 'Failed to add item to cart! ' . $e->getMessage());
            } else {

                Alert::error('Error', 'Failed to add item to cart!');
            }
            return redirect()->back();
        }
    }

    public function incrementCart(Request $request, $itemsId)
    {
        try {
            \DB::beginTransaction();

            $cartItem = CartItem::find($itemsId);
            if (!$cartItem) {
                Alert::error('Error', 'Cart item not found!');
                return response()->json(['error' => 'Cart item not found!'], 404);
            }

            $cartItem->increment('quantity');
            $cartItem->save();

            \DB::commit();

            return response()->json(['success' => 'Item quantity incremented successfully!', 'new_quantity' => $cartItem->quantity]);
        } catch (\Exception $e) {
            \DB::rollBack();
            if (app()->environment('local')) {
                Alert::error('Error', 'Failed to increment item quantity! ' . $e->getMessage());
            } else {
                Alert::error('Error', 'Failed to increment item quantity!');
            }
            return response()->json(['error' => 'Failed to increment item quantity!'], 500);
        }
    }

    // decrement by ajax
    public function decrementCart(Request $request, $itemsId)
    {
        try {
            \DB::beginTransaction();

            $cartItem = CartItem::find($itemsId);
            if (!$cartItem) {
                Alert::error('Error', 'Cart item not found!');
                return response()->json(['error' => 'Cart item not found!'], 404);
            }

            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
                $cartItem->save();
            } else {
                // If quantity is 1, you might want to remove the item from the cart
                $cartItem->delete();
            }

            \DB::commit();

            return response()->json(['success' => 'Item quantity decremented successfully!', 'new_quantity' => $cartItem->quantity]);
        } catch (\Exception $e) {
            \DB::rollBack();
            if (app()->environment('local')) {
                Alert::error('Error', 'Failed to decrement item quantity! ' . $e->getMessage());
            } else {
                Alert::error('Error', 'Failed to decrement item quantity!');
            }
            return response()->json(['error' => 'Failed to decrement item quantity!'], 500);
        }
    }
    // remove by ajax
    public function removeCart(Request $request, $itemsId)
    {
        try {
            \DB::beginTransaction();

            $cartItem = CartItem::find($itemsId);
            if (!$cartItem) {
                Alert::error('Error', 'Cart item not found!');
                return response()->json(['error' => 'Cart item not found!'], 404);
            }

            $cartItem->delete();

            \DB::commit();

            return response()->json([
                'success' => 'Item removed from cart successfully!',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            if (app()->environment('local')) {
                Alert::error('Error', 'Failed to remove item from cart! ' . $e->getMessage());
            } else {
                Alert::error('Error', 'Failed to remove item from cart!');
            }
            return response()->json(['error' => 'Failed to remove item from cart!'], 500);
        }
    }

    public function cartCheckout(Request $request)
    {
        try {
            // \DB::beginTransaction();

            // return $request->all();
            $user_cart = Cart::where('user_id', Auth::user()->id)->first();
            if (!$user_cart || $user_cart->items->isEmpty()) {
                Alert::error('Error', 'Your cart is empty!');
                return redirect()->back();
            }
            // selected_items
            $selectedItems = $request->input('selected_items', []); // Get selected items from the request
            if (empty($selectedItems)) {
                Alert::error('Error', 'No items selected for checkout!');
                return redirect()->back();
            }

            // 

            // Here you can implement your checkout logic, e.g., creating an order, processing payment, etc.

            // Clear the cart after checkout
            // $user_cart->items()->delete();

            // \DB::commit();


            return redirect()->route('user.checkout.index', [
                'selected_items' => $selectedItems,
                'cart' => $user_cart,
            ]); // Redirect to a suitable page after checkout
        } catch (\Exception $e) {
            // \DB::rollBack();
            if (app()->environment('local')) {
                Alert::error('Error', 'Failed to complete checkout! ' . $e->getMessage());
            } else {
                Alert::error('Error', 'Failed to complete checkout!');
            }
            return redirect()->back();
        }
    }
}
