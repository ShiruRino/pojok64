@extends('layouts.app')
@section('title', 'Transaction #' . $transaction->id)
@section('content')

<a href="{{ route('transactions.index') }}" class="btn btn-danger mb-3">Back</a>

<div class="card mb-3">
    <div class="card-header">Transaction #{{ $transaction->id }}</div>

    <div class="card-body">
        <ul class="list-group list-group-flush">

            <li class="list-group-item">
                <strong>Order ID:</strong> {{ $transaction->order_id }}
            </li>

            <li class="list-group-item">
                @php
                    $total = $transaction->order->detailOrders
                        ->sum(fn($d) => $d->product->price * $d->quantity);
                @endphp
                <strong>Total:</strong> Rp{{ number_format($total, 0, ',', '.') }}
            </li>

            <li class="list-group-item">
                <strong>Paid:</strong> Rp{{ number_format($transaction->amount_paid, 0, ',', '.') }}
            </li>

            <li class="list-group-item">
                <strong>Change:</strong> Rp{{ number_format($transaction->change, 0, ',', '.') }}
            </li>

            <li class="list-group-item">
                <strong>Payment Method:</strong> {{ strtoupper($transaction->payment_method) }}
            </li>

        </ul>
    </div>
</div>

<div class="card">
    <div class="card-header">Order Details</div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transaction->order->detailOrders as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td>Rp{{ number_format($total, 0, ',', '.') }}</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@endsection
