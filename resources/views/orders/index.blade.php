@extends('layouts.app')
@section('title', 'Order List')
@section('content')
    <a href="{{ route('orders.create') }}" class="btn btn-success mb-3">Add</a>
    <div class="card">
        <div class="card-header">Order List</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Detail Order</th>
                        <th>Total</th>
                        <th>Notes</th>
                        <th>Payment Method</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>
                            <ul>
                                @foreach ($order->detailOrders as $detail)
                                <li>{{ $detail->product->name }} (x{{ $detail->quantity }})(Rp{{ number_format($detail->subtotal, 0, '', '.') }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>Rp{{ number_format($order->total, 0, '','.') }}</td>
                        <td>{{ $order->notes ?? '-' }}</td>
                        <td>{{ Str::upper($order->payment_method) ?? '-' }}</td>
                        <td class="d-flex flex-wrap" style="gap: 0.5rem">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Show</a>
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">{{ $orders->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
