@extends('layouts.app')
@section('title', 'Edit Order #' . $order->id)

@section('content')

<a href="{{ route('orders.index') }}" class="btn btn-danger mb-3">Back</a>

<div class="card mb-5">
    <div class="card-header">Edit Order #{{ $order->id }}</div>

    <div class="card-body">

        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text"
                       name="customer_name"
                       class="form-control"
                       placeholder="Input customer's name"
                       value="{{ old('customer_name', $order->customer_name) }}">
            </div>

            <div id="product-container">
                @php
                    $oldProducts = old('products', $order->detailOrders->pluck('product_id')->toArray());
                    $oldQty = old('quantity', $order->detailOrders->pluck('quantity')->toArray());
                @endphp

                @foreach ($oldProducts as $i => $oldProductId)
                <div class="row g-3 mb-3 product-row">

                    <div class="col-12 col-md-6">
                        <label class="form-label">Product</label>
                        <select name="products[]" class="form-select">
                            <option disabled selected>--- Select Product ---</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ $oldProductId == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} - Rp{{ number_format($product->price,0,',','.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Quantity</label>
                        <input type="number"
                               name="quantity[]"
                               class="form-control"
                               min="1"
                               value="{{ $oldQty[$i] ?? 1 }}">
                    </div>

                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100 remove-row">Remove</button>
                    </div>

                </div>
                @endforeach
            </div>

            <button type="button" id="add-product" class="btn btn-secondary mb-3 w-100">
                + Add Product
            </button>

            <button type="submit" class="btn btn-success w-100">Update</button>

        </form>
    </div>
</div>

<script>
document.getElementById('add-product').addEventListener('click', function () {
    const container = document.getElementById('product-container');
    const firstRow = container.querySelector('.product-row');
    const newRow = firstRow.cloneNode(true);

    newRow.querySelectorAll('select, input').forEach(input => {
        if (input.tagName === 'SELECT') {
            input.selectedIndex = 0;
        } else {
            input.value = 1;
        }
    });

    container.appendChild(newRow);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        const row = e.target.closest('.product-row');
        const container = document.getElementById('product-container');

        if (container.querySelectorAll('.product-row').length > 1) {
            row.remove();
        }
    }
});
</script>

@endsection
