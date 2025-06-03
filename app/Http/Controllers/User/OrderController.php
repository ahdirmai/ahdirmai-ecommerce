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

        try {
            \DB::beginTransaction();

            $payment = Payment::where('order_id', $order->id)->firstOrFail();
            $payment_history = PaymentHistory::create([
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
                'amount' => $request->amount, // Assuming amount is stored in the payment model
                'payment_method' => $payment->payment_method,
                'bank_receiver' => $request->input('bank_account_id'), // e.g., 'Bank Mandiri' 
                // e.g., 'bank_transfer'
                'bank_name' => $request->input('sender_bank'), // e.g., 'Bank Mandiri'
                'sender_name' => $request->input('sender_name'), // e.g., 'John Doe'
                'status' => 'pending', // Set initial status to pending
            ]);

            // Handle file upload using spatie media library accociate with payment history
            if ($request->hasFile('proof')) {
                $file = $request->file('proof');
                // Store the file in the 'proofs' collection
                $payment_history->addMedia($file)->toMediaCollection('proofs');
            } else {
                \DB::rollBack();
                return redirect()->back()->withErrors(['proof' => 'Proof of payment is required.']);
            }

            \DB::commit();

            Alert::success('Success', 'Proof of payment uploaded successfully.');

            // Show success message
            return redirect()->route('user.order.show', ['order' => $order])->with('success', 'Proof of payment uploaded successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            if (app()->environment('local')) {

                Alert::error('Error', 'Failed to upload proof of payment: ' . $e->getMessage());
                # code...
            } else {

                Alert::error('Error', 'Failed to upload proof of payment. Please try again.');
            }

            return redirect()->back()->withErrors(['error' => 'Failed to upload proof of payment. Please try again.']);
        }
    }

    // receivedOrder
    public function receivedOrder(Request $request, Order $order)
    {
        // Validate the request
        // $request->validate([
        //     'received' => 'required|boolean',
        // ]);

        try {
            \DB::beginTransaction();

            // Update order status to 'completed'
            $order->update(['status' => 'completed']);

            \DB::commit();

            Alert::success('Success', 'Order marked as received successfully.');

            return redirect()->route('user.order.show', ['order' => $order])->with('success', 'Order marked as received successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            Alert::error('Error', 'Failed to mark order as received: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to mark order as received. Please try again.']);
        }
    }
}
