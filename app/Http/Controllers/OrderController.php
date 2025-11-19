<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status) {
            $orders = Order::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Order::doesntHave('transaction')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $selectedProductId = $request->query('product_id');
        $products = Product::orderBy('name')->get();

        return view('orders.create', compact('products', 'selectedProductId'));
    }

    public function store(Request $request)
    {
        $rules = [
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'status' => 'pending',
            'total' => 0,
            'notes' => trim($request->notes),
        ]);

        foreach ($request->products as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];
            if($product->stock < $quantity){
                return back()->with('error', 'Insufficient stock');
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

        return redirect()->route('orders.index')->with('success', 'Order created.');
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $products = Product::orderBy('name')->get();
        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $rules = [
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Restore stock from old order
        foreach ($order->detailOrders as $d) {
            $d->product->stock += $d->quantity;
            $d->product->save();
            $d->delete();
        }

        // Update main order
        $order->customer_name = $request->customer_name;
        $order->notes = trim($request->notes);
        $order->total = 0;
        $order->save();

        // Add new order details
        foreach ($request->products as $index => $productId) {

            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];

            // Check stock before reducing
            if ($product->stock < $quantity) {
                return back()->with('error', 'Insufficient stock');
            }

            $subtotal = $product->price * $quantity;

            DetailOrder::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);

            // Reduce stock
            $product->stock -= $quantity;
            $product->save();
        }

        // Recalculate total
        $order->total = $order->detailOrders()->sum('subtotal');
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order updated.');
    }


    public function destroy(Order $order)
    {
        if (!$order->transaction) {
            foreach ($order->detailOrders as $d) {
                $d->product->stock += $d->quantity;
                $d->product->save();
            }
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
