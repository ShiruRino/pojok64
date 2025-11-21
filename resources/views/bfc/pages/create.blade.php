@extends('bfc.layouts.app')
@section('title', 'Add a new order')
@section('content')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const code = localStorage.getItem('guest_order_code');
    const submitted = localStorage.getItem('submitted');
    if (code && submitted) {
        window.location.href = "{{ url('/order') }}/" + encodeURIComponent(code);
    }
});
</script>


<div class="card mb-5">
    <div class="card-header">Add a new order</div>
    <div class="card-body">

        <form id="orderForm" action="{{ route('user.order.store') }}" method="POST" onsubmit="addSubmitted()">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="customer_name" class="form-control"
                       placeholder="Input customer's name"
                       value="{{ old('customer_name') }}">
            </div>

            <!-- Order Code -->
            <div class="mb-3">
                <label class="form-label">Order Code</label>
                <div class="d-flex gap-2">
                    <input type="text" id="orderCodeDisplay" class="form-control" disabled>
                    <button type="button" id="generateCodeBtn" class="btn btn-primary">Generate</button>
                </div>
                <input type="hidden" name="order_code" id="orderCode">
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

            <!-- Payment Method -->
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" cols="30" rows="5" class="form-control" placeholder="Input notes">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">Submit</button>

        </form>

    </div>
</div>

<script>
(function () {
    // helper: read cart (support both keys used earlier)
    const readCart = () => {
        return JSON.parse(localStorage.getItem('cart_items'))
            || JSON.parse(localStorage.getItem('cart'))
            || [];
    };
    function addSubmitted(){
        localStorage.setItem('submitted', true);
    }
    const generateOrderCode = () => "BFC-" + Math.floor(100000 + Math.random() * 900000);

    document.addEventListener('DOMContentLoaded', () => {

        // redirect if user already submitted a guest order
        const code = localStorage.getItem('guest_order_code');
        const submitted = localStorage.getItem('submitted');
        if (code && submitted) {
            window.location.href = "{{ url('/order') }}/" + encodeURIComponent(code);
            return;
        }

        // helpers
        const readCart = () => {
            return JSON.parse(localStorage.getItem('cart_items'))
                || JSON.parse(localStorage.getItem('cart'))
                || [];
        };
        const generateOrderCode = () => "BFC-" + Math.floor(100000 + Math.random() * 900000);

        // elements
        const container = document.getElementById('product-container');
        const addBtn = document.getElementById('add-product');
        const generateBtn = document.getElementById('generateCodeBtn');
        const displayCode = document.getElementById('orderCodeDisplay');
        const hiddenCode = document.getElementById('orderCode');

        // handle existing code
        if (code) {
            displayCode.value = code;
            hiddenCode.value = code;
            generateBtn.style.display = 'none';
        }

        // generate new code
        generateBtn.addEventListener('click', () => {
            const newCode = generateOrderCode();
            displayCode.value = newCode;
            hiddenCode.value = newCode;
            localStorage.setItem('guest_order_code', newCode);
            generateBtn.style.display = 'none';
        });

        // prepare template row
        const templateRow = container.querySelector('.product-row').cloneNode(true);

        // clean template
        templateRow.querySelectorAll('select, input').forEach(el => {
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            if (el.type === 'number') el.value = 1;
        });

        // add row function
        function addRow(productId = null, qty = 1) {
            const row = templateRow.cloneNode(true);

            const select = row.querySelector('select[name="products[]"]');
            const quantity = row.querySelector('input[name="quantity[]"]');

            if (productId) {
                Array.from(select.options).forEach(o => {
                    o.selected = String(o.value) === String(productId);
                });
            }

            quantity.value = qty;

            // remove handler
            row.querySelector('.remove-row').addEventListener('click', () => {
                row.remove();
            });

            container.appendChild(row);
        }

        // active remove buttons on initial row
        container.querySelectorAll('.remove-row').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.product-row').remove();
            });
        });

        // load cart items from localStorage
        const cart = readCart();
        if (cart.length > 0) {
            container.innerHTML = ''; // clear everything

            cart.forEach(item => {
                addRow(item.product_id, item.quantity);
            });
        }

        // add product button
        addBtn.addEventListener('click', () => {
            addRow();
        });

        // submitted flag
        window.addSubmitted = () => {
            localStorage.setItem('submitted', true);
        };
    });
})();
</script>


@endsection
