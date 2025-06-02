<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    public function  index()
    {
        // get all orders for the authenticated user
        $orders = Order::where('user_id', auth()->id())->with('items.product')->get();

        $order_pending = $orders->where('status', 'pending');


        $data = [
            'orders' => $orders, // Orders with status 'pending'
        ];
        return view('user.order.index', $data);
    }
    public function show(Order $order)
    {

        $data = [
            'order' => $order->load('items.product', 'payment', 'payment.paymentHistories'), // Load order items with product details
        ];
        return view('user.order.show', $data);
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
        $payment_history = PaymentHistory::create([
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'amount' => $payment->amount, // Assuming amount is stored in the payment model
            'payment_method' => $payment->payment_method, // e.g., 'bank_transfer'
            'bank_name' => $request->input('bank_account_id'), // e.g., 'Bank Mandiri'
            'sender_name' => $request->input('sender_name'), // e.g., 'John Doe'
            'status' => 'pending', // Set initial status to pending
        ]);

        // Handle file upload using spatie media library accociate with payment history
        if ($request->hasFile('proof')) {
            $file = $request->file('proof');
            // Store the file in the 'proofs' collection
            $payment_history->addMedia($file)->toMediaCollection('proofs');
        } else {
            return redirect()->back()->withErrors(['proof' => 'Proof of payment is required.']);
        }

        Alert::success('Success', 'Proof of payment uploaded successfully.');

        // Show success message
        return redirect()->route('user.order.show', ['order' => $order])->with('success', 'Proof of payment uploaded successfully.');
    }
}
