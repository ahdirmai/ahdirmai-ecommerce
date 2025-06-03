<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
    //     Route::get('/', [OrderOrderController::class, 'index'])->name('index');
    //     Route::get('/{order}', [OrderOrderController::class, 'show'])->name('show');
    //     Route::post('/{order}/{payment_history}/update-status', [OrderOrderController::class, 'updateStatus'])->name('update-status');
    // });
    public function index()
    {
        $orders = Order::all(); // Retrieve all orders from the database
        // Logic to retrieve and display a list of orders
        $data = [
            'orders' => $orders,
        ];
        return view('admin.pages.orders.index', $data);
    }

    public function show(Order $order)
    {
        // $order = Order::where('order_numbe'); // Retrieve the order by its ID
        $data = [
            'order' => $order,
        ];
        // Logic to retrieve and display a specific order by its ID
        return view('admin.pages.orders.show', $data);
    }

    public function updateStatus(Request $request, Order $order, PaymentHistory $paymentHistory)
    {

        try {
            \DB::beginTransaction();

            // update PaymentHistory status
            $paymentHistory->update([
                'status' => \Str::lower($request->input('status')),
            ]);

            $payment = $paymentHistory->payment;

            // jika history pembayaran complete
            if ($paymentHistory->status === 'Completed') {

                // return $paymentHistory;
                $paidAmount = $paymentHistory->amount;
                $payment->update([
                    'amount_paid' => $payment->amount_paid + $paidAmount,
                ]);

                // return $payment->amount;

                if ((float)$payment->amount_paid >= (float) $payment->amount) {
                    // return 'y';
                    $payment->update([
                        'status' => 'completed',
                    ]);
                    $order->update([
                        'status' => 'processing',
                    ]);
                } else {
                    $payment->update([
                        'status' => 'partial',
                    ]);
                }
            }

            \DB::commit();
            return redirect()->route('admin.orders.show', ['order' => $order])->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function updatePaymentAmount(Request $request, PaymentHistory $history)
    {

        // return $request->all();
        try {
            \DB::beginTransaction();

            $order = $history->payment->order;
            $history->update([
                'amount' => $request->input('amount'),
            ]);

            \DB::commit();
            return redirect()->route('admin.orders.show', ['order' => $order])->with('success', 'Payment amount updated successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update payment amount: ' . $e->getMessage());
        }
    }
}
