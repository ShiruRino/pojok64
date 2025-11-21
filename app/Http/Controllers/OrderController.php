<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();
        try {

            $order = Order::create([
                'code' => 'cashiercreated',
                'customer_name' => $request->customer_name,
                'status' => 'pending',
                'total' => 0,
                'notes' => trim($request->notes),
            ]);

            foreach ($request->products as $index => $productId) {
                $product = Product::lockForUpdate()->findOrFail($productId);
                $quantity = $request->quantity[$index];

                if ($product->stock < $quantity) {
                    DB::rollBack();
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

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
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
        // $rules = [
        //     'customer_name' => 'required|string|max:255',
        //     'products' => 'required|array',
        //     'products.*' => 'required|exists:products,id',
        //     'quantity.*' => 'required|integer|min:1',
        //     'notes' => 'nullable|string|max:255'
        // ];

        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        // DB::beginTransaction();
        // try {

        //     foreach ($order->detailOrders as $d) {
        //         $p = Product::lockForUpdate()->find($d->product_id);
        //         $p->stock += $d->quantity;
        //         $p->save();
        //         $d->delete();
        //     }

        //     $order->customer_name = $request->customer_name;
        //     $order->notes = trim($request->notes);
        //     $order->total = 0;
        //     $order->save();

        //     foreach ($request->products as $index => $productId) {

        //         $product = Product::lockForUpdate()->findOrFail($productId);
        //         $quantity = $request->quantity[$index];

        //         if ($product->stock < $quantity) {
        //             DB::rollBack();
        //             return back()->with('error', 'Insufficient stock');
        //         }

        //         $subtotal = $product->price * $quantity;

        //         DetailOrder::create([
        //             'order_id' => $order->id,
        //             'product_id' => $product->id,
        //             'quantity' => $quantity,
        //             'subtotal' => $subtotal,
        //         ]);

        //         $product->stock -= $quantity;
        //         $product->save();
        //     }

        //     $order->total = $order->detailOrders()->sum('subtotal');
        //     $order->save();

        //     DB::commit();
        //     return redirect()->route('orders.index')->with('success', 'Order updated.');

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Something went wrong.');
        // }
        $rules = [
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order status was updated.');

    }



    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {

            if (!$order->transaction) {
                foreach ($order->detailOrders as $d) {
                    $p = Product::lockForUpdate()->find($d->product_id);
                    $p->stock += $d->quantity;
                    $p->save();
                }
            }

            $order->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
    }

}
