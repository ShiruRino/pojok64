<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
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
        if(Order::where('code', $request->order_code)->doesntHave('transaction')->exists()){
            return back()->with('error', 'An order with the same code found, please regenerate code.')->withInput();
        }
        $order = Order::create([
            'code' => $request->order_code,
            'customer_name' => $request->customer_name,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'total' => 0,
            'notes' => trim($request->notes),
        ]);

        foreach ($request->products as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];
            if($product->stock < $quantity){
                return back()->with('error', 'Insufficient stock. Stock left: ' . $product->stock);
            }
            $subtotal = $product->price * $quantity;

            DetailOrder::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);
            $product->stock -= $quantity;
            $product->save();
        }

        $order->total = $order->detailOrders()->sum('subtotal');
        $order->save();

        return redirect()->route('user.home')->with('success', 'Order created.');
    }
}
