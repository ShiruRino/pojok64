@extends('layouts.app')
@section('title', 'Order #' . $order->id)
@section('content')

<a href="{{ route('orders.index') }}" class="btn btn-danger mb-3">Back</a>

<div class="card mb-3">
    <div class="card-header">Order #{{ $order->id }}</div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>Customer's Name:</strong> {{ $order->customer_name }}
            </li>
            <li class="list-group-item">
                <strong>Order Status:</strong> {{ Str::upper($order->status) }}
            </li>
            <li class="list-group-item">
                <strong>Total:</strong> {{ number_format($order->total, 0, '', '.') }}
            </li>
            <li class="list-group-item">
                <strong>Notes:</strong> {{ $order->notes }}
            </li>
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-header">Detail Order</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->detailOrders as $i)
                <tr>
                    <td>{{ $i->product->name }}</td>
                    <td>{{ $i->quantity }}</td>
                    <td>Rp{{ number_format($i->product->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($i->product->price * $i->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                @php
                    $total = $order->detailOrders->sum(fn($i) => $i->product->price * $i->quantity);
                @endphp

                <tr>
                    <td colspan="3" style="text-align:right"><strong>Total:</strong></td>
                    <td>Rp{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
