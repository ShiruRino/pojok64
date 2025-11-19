@extends('bfc.layouts.app')
@section('title', 'Add a new order')
@section('content')


<div class="card mb-5"> <div class="card-header">Add a new order</div> <div class="card-body">
    <form action="{{ route('user.order.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="customer_name" class="form-control"
                   placeholder="Input customer's name"
                   value="{{ old('customer_name') }}">
        </div>

        <div id="product-container">
            @php
                $oldProducts = old('products', [null]);
                $oldQty = old('quantity', [1]);
            @endphp

            @foreach ($oldProducts as $i => $oldProductId)
            <div class="row g-3 mb-3 product-row">

                <div class="col-12 col-md-6">
                    <label class="form-label">Product</label>
                    <select name="products[]" class="form-select">
                        <option disabled selected>--- Select Product ---</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ $oldProductId ?? $product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} - Rp{{ number_format($product->price,0,',','.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control"
                        min="1" value="{{ $oldQty[$i] ?? 1 }}">
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

         <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

        <button type="submit" class="btn btn-success w-100">Submit</button>
    </form>

</div>

</div>
<script>
document.getElementById('add-product').addEventListener('click', function () { const container = document.getElementById('product-container'); const firstRow = container.querySelector('.product-row'); const newRow = firstRow.cloneNode(true); newRow.querySelectorAll('select, input').forEach(input => { if (input.tagName === 'SELECT') { input.selectedIndex = 0; } else { input.value = 1; } }); container.appendChild(newRow); }); document.addEventListener('click', function (e) { if (e.target.classList.contains('remove-row')) { const row = e.target.closest('.product-row'); const container = document.getElementById('product-container'); if (container.querySelectorAll('.product-row').length > 1) { row.remove(); } } });
document.getElementById('orderForm').addEventListener('submit', function(e) {


    e.preventDefault();

    const form = e.target;

    const customerName = form.customer_name.value;
    const paymentMethod = form.payment_method.value;

    const products = [];
    const productRows = document.querySelectorAll('.product-row');

    productRows.forEach(row => {
        products.push({
            product_id: row.querySelector('select[name="products[]"]').value,
            quantity: row.querySelector('input[name="quantity[]"]').value
        });
    });

    const orderData = {
        customer_name: customerName,
        payment_method: paymentMethod,
        products: products,
        created_at: new Date().toISOString()
    };

    localStorage.setItem('guest_order', JSON.stringify(orderData));

    alert('Order saved locally.');
    window.location.href = "{{ route('user.home') }}";
});

</script>

@endsection
