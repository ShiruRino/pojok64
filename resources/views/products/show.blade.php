@extends('layouts.app')
@section('title', $product->name)
@section('content')

<a href="{{ route('products.index') }}" class="btn btn-danger mb-3">Back</a>

<div class="card mb-3"> <div class="card-header">{{ $product->name }}</div> <div class="card-body">
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Name. </strong>{{ $product->name }}</li>
        <li class="list-group-item"><strong>Description. </strong><br>{{ $product->description }}</li>
        <li class="list-group-item">
            <strong>Price. </strong>Rp{{ number_format($product->price,0,',','.') }}
        </li>
        <li class="list-group-item"><strong>Stock. </strong>{{ $product->stock }}</li>
    </ul>

    <div class="row mt-3 g-3">
        @foreach ($product->images as $image)
            <div class="col-6 col-md-3">
                <img src="{{ url('storage/'.$image) }}"
                     class="img-fluid rounded border"
                     alt="Image {{ $loop->iteration }}">
            </div>
        @endforeach
    </div>

</div>

</div>

<a href="{{ route('orders.create', ['product_id' => $product->id]) }}" class="btn btn-success mb-3">
Add a new order for this product
</a>

@if ($orders->count() > 0)

<div class="card"> <div class="card-header">Order List</div> <div class="card-body">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Detail Order</th>
                <th>Total</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->customer_name }}</td>
                <td>
                    <ul class="m-0">
                        @foreach ($order->detailOrders as $detail)
                        <li>
                            {{ $detail->product->name }}
                            x{{ $detail->quantity }}
                            (Rp{{ number_format($detail->subtotal,0,',','.') }})
                        </li>
                        @endforeach
                    </ul>
                </td>
                <td>Rp{{ number_format($order->total,0,',','.') }}</td>
                <td>{{ $order->notes }}</td>
                <td>
                    <div class="d-flex flex-wrap" style="gap: 0.5rem">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">Show</a>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('orders.destroy', $order->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>

</div>

</div> @endif

@endsection
