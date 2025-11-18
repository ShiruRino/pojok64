<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->query('status')) {
            $orders = Order::where('status', $request->query('status'))->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $orders = Order::doesntHave('transaction')->orderBy('created_at', 'desc')->paginate(10);
        }
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('orders.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'status' => 'pending',
            'total' => 0,
        ]);
        foreach($request->products as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $request->quantity[$index];
            $subtotal = $product->price * $quantity;
            DetailOrder::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);
        }
        $order->total = $order->detailOrders()->sum('subtotal');
        $order->save();
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
