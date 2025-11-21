<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris',
            'notes' => 'nullable|string|max:255',
            'order_code' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Order code must be unique
        if (Order::where('code', $request->order_code)->exists()) {
            return back()->with('error', 'Order code already used. Generate a new one.')->withInput();
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'code' => $request->order_code,
                'customer_name' => $request->customer_name,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total' => 0,
                'notes' => trim($request->notes),
            ]);

            foreach ($request->products as $i => $productId) {

                $product = Product::findOrFail($productId);
                $qty = $request->quantity[$i];

                if ($product->stock < $qty) {
                    DB::rollBack();
                    return back()->with('error', 'Insufficient stock for '. $product->name .'. Stock left: '.$product->stock)->withInput();
                }

                $subtotal = $product->price * $qty;

                DetailOrder::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                $product->stock -= $qty;
                $product->save();
            }

            $order->total = $order->detailOrders()->sum('subtotal');
            $order->save();

            DB::commit();

            return redirect()->route('user.order.index', $request->order_code)
                ->with('success', 'Order created. Show the order code to cashier.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
    }
    public function updateAllQuantities(Request $request, Order $order)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        if ($order->status !== 'pending') {
            return back()->with('error', "You can not update the quantity, your order is being processed");
        }

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {

                $detail = $order->detailOrders()
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$detail) {
                    continue;
                }

                $product = $detail->product;

                // Check stock
                if ($product->stock + $detail->quantity < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', 'Insufficient stock for ' . $product->name . '. Stock left: ' . $product->stock);
                }

                // Restore previous stock
                $product->stock += $detail->quantity;

                // Deduct new quantity
                $product->stock -= $item['quantity'];
                $product->save();

                // Update detail
                $detail->quantity = $item['quantity'];
                $detail->subtotal = $item['quantity'] * $product->price;
                $detail->save();
            }

            // Update order total
            $order->total = $order->detailOrders()->sum('subtotal');
            $order->save();

            DB::commit();

            return back()->with('success', 'Order updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
    }
    public function guestCancel(Order $order)
    {
        if ($order->status != 'pending') {
        return back()->with('error', "You can not cancel, this order is being processed");
        }

        DB::beginTransaction();

        try {
            foreach ($order->detailOrders as $detail) {
                $detail->product->stock += $detail->quantity;
                $detail->product->save();
            }

            $order->delete();

            DB::commit();

            return redirect()->route('user.home');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Something went wrong. Try again.');
        }
    }




}
