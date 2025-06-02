<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Route::get('/order/{order}', [OrderController::class, 'show'])->name('user.order.show');
    // Route::get('/order/{order}/upload-proof', [OrderController::class, 'uploadProof'])->name('user.checkout.upload-proof');
    // Route::POST('/order/{order}/upload-proof', [OrderController::class, 'uploadProof'])->name('user.checkout.upload-proof');
    // Route::post('/order/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('user.order.cancel');
    public function show($order)
    {
        // Logic to show order details
        // You can use the Order model to fetch the order by its ID or order number
        // return view('user.order.show', compact('order'));
    }
    public function getUploadProof(Order $order)
    {
        // return $order;
        $bankAccounts = [
            ['bank_name' => 'Bank Mandiri', 'account_number' => '1234567890', 'account_name' => 'John Doe'],
            ['bank_name' => 'Bank BCA', 'account_number' => '0987654321', 'account_name' => 'Jane Doe'],
        ];

        $data = [
            'order' => $order->load('items.product'), // Load order items with product details
            'bankAccounts' => $bankAccounts, // Dummy data for bank accounts
        ];


        return view('user.order.upload-proof', $data);
    }
    public function uploadProof(Request $request, Order $order)
    {
        // return $order;
        // return $request->all();
        // Validate the request
        $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $payment = Payment::where('order_id', $order->id)->firstOrFail();
        // $table->unsignedBigInteger('user_id');
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        // $table->unsignedBigInteger('payment_id');
        // $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        // $table->decimal('amount', 10, 2)->default(0);
        // $table->string('payment_method')->nullable();
        // $table->string('bank_name')->nullable();
        // $table->string('sender_name')->nullable();
        // $table->string('status')->default('pending');
        // create payment history
        $payment->paymentHistories()->create([
            'user_id' => auth()->id(),
            'amount' => $payment->amount, // Assuming amount is stored in the payment model
            'payment_method' => $payment->payment_method, // e.g., 'bank_transfer'
            'bank_name' => $request->input('bank_account_id'), // e.g., 'Bank Mandiri'
            'sender_name' => $request->input('sender_name'), // e.g., 'John Doe'
            'status' => 'pending', // Set initial status to pending
        ]);


        // Show success message
        return redirect()->route('user.order.show', ['order' => $order])->with('success', 'Proof of payment uploaded successfully.');
    }
}
