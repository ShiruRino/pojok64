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
                        <th>Status</th>
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
                        <td>
                            {{-- @if ($order->status == 'pending' || $order->status =='processing')
                            <span class="badge bg-warning">{{ strtoupper($order->status) }}</span>
                            @elseif ($order->status == 'ready' || $order->status =='completed')
                            <span class="badge bg-success">{{ strtoupper($order->status) }}</span>
                            @elseif ($order->status == 'cancelled')
                            <span class="badge bg-danger">{{ strtoupper($order->status) }}</span>
                            @endif --}}
                            <form action="{{ route('orders.update',$order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select" onchange="this.form.submit()">

                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>PROCESSING</option>
                                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>READY</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>COMPLETED</option>

                                </select>
                            </form>

                        </td>
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
                            {{-- <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit</a> --}}
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
