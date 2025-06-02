<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderShippingAddress;
use App\Models\Payment;
use App\Models\ShippingAddress;
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
            'shippingAddress' => auth()->user()->addresses,
            'cart' => $cart,
            'selectedItems' => $selectedItems,
            'totalPrice' => $selectedItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            }),
            'totalQuantity' => $selectedItems->sum('quantity'),
        ];


        return view('user.checkout.index', $data);
    }

    public function process(Request $request)
    {
        // Validate the request
        $request->validate([
            'shipping_address_id' => 'required|exists:shipping_addresses,id',
            'payment_method' => 'required|string',
            'cart_items' => 'required|array',
        ]);

        try {
            \DB::beginTransaction();

            $cartItems = CartItem::whereIn('id', $request->cart_items)->get();
            if ($cartItems->isEmpty()) {
                Alert::error('Error', 'No items found in the cart!');
                return redirect()->route('user.cart.index');
                # code...
            }
            $total_amount = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            $shippingAddress = ShippingAddress::find($request->shipping_address_id);

            // return 'ORD-' . strtoupper(uniqid()) . '-' . now()->timestamp;
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()) . '-' . now()->timestamp,
                'user_id' => auth()->id(),
                'total_amount' => $total_amount,
                // 'payment_method' => $request->payment_method,
                'status' => 'pending',
                'shipping_address' => $shippingAddress->full_address,
            ]);

            $order->payment()->create([
                'order_id' => $order->id,
                'amount' => $total_amount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'ammount_paid' => 0, // Set this after payment gateway response
                'transaction_id' => null, // Set this after payment gateway response
                // 'transaction_id' => null, // Set this after payment gateway response
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->product->price * $item->quantity,
                ]);
            }

            $order->OrderShippingAddress()->create([
                'order_id' => $order->id,
                'address_line1' => $shippingAddress->address_line1,
                'address_line2' => $shippingAddress->address_line2,
                'city' => $shippingAddress->city,
                'state' => $shippingAddress->state,
                'postal_code' => $shippingAddress->postal_code,
                'country' => $shippingAddress->country,
                'phone_number' => $shippingAddress->phone_number,
                'email' => $shippingAddress->email,
                'label' => $shippingAddress->label,
            ]);

            $cartItems->each(function ($item) {
                $item->delete();
            });


            \DB::commit();
            Alert::success('Success', 'Order processed successfully!');
            return redirect()->route('user.order.get-upload-proof', ['order' => $order]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $e->getMessage();
            Alert::error('Error', 'Checkout failed: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
