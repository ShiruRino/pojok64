@extends('bfc.layouts.app')
@section('title','Your Orders')
@section('content')

@if ($order)

<h3 class="mb-3">Order Details</h3>

<div class="card mb-4">
    <div class="card-body d-flex flex-column gap-2">
        <span class="fw-bold">{{ $order->customer_name }}</span>
        <span class="fw-bold">Order code: {{ ucfirst($order->code) }}</span>
        <span class="fw-bold">Status:
            @if ($order->status == 'pending' || $order->status =='processing')
            <span class="badge bg-warning">{{ strtoupper($order->status) }}</span>
            @elseif ($order->status == 'ready' || $order->status =='completed')
            <span class="badge bg-success">{{ strtoupper($order->status) }}</span>
            @elseif ($order->status == 'cancelled')
            <span class="badge bg-danger">{{ strtoupper($order->status) }}</span>
            @endif

        </span>
        <span class="fw-bold">Payment: {{ strtoupper($order->payment_method) }}</span>
        <span class="fw-bold">Total: Rp{{ number_format($order->total, 0, '', '.') }}</span>
        @if($order->notes)
            <span class="fw-bold">Notes: {{ $order->notes }}</span>
        @endif
        @if ($order->payment_method == 'qris')
            <img src="{{ asset('storage/qris/qris.png') }}" alt="QRIS" width="250px" height="250px">
        @endif
    </div>
</div>

<h3 class="mb-3">Order Summary</h3>

<form id="updateForm" action="{{ route('user.order.updateAll', $order->id) }}" method="POST">
    @csrf
    @method('PATCH')

    @foreach($order->detailOrders as $item)
    <div class="card mb-3 item-card">

        <div class="card-body d-flex">

            <img src="{{ asset('storage/' . $item->product->images[0] ?? '-') }}"
                 alt="image"
                 class="rounded"
                 style="width: 100px; height: 100px; object-fit: cover;">

            <div class="ms-3 flex-grow-1 d-flex flex-column justify-content-between">

                <div>
                    <div class="fw-bold">{{ $item->product->name }}</div>
                    <div class="text-muted small">{{ $item->product->description }}</div>
                    <div class="fw-bold mt-1">Rp{{ number_format($item->product->price, 0, '', '.') }}</div>
                    <div class="fw-bold mt-1 subtotal" data-price="{{ $item->product->price }}">
                        Subtotal: Rp{{ number_format($item->subtotal, 0, '', '.') }}
                    </div>
                </div>

                <div class="d-flex align-items-center mt-2">

                    <button type="button" class="btn btn-outline-danger btn-sm minus">-</button>

                    <span class="fw-bold mx-2 quantity">{{ $item->quantity }}</span>

                    <button type="button" class="btn btn-outline-success btn-sm plus">+</button>

                    <input type="hidden" name="items[{{ $item->product->id }}][product_id]" value="{{ $item->product->id }}">
                    <input type="hidden" class="qtyInput" name="items[{{ $item->product->id }}][quantity]" value="{{ $item->quantity }}">

                </div>

            </div>

        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
        <span>Total</span>
        <span id="totalDisplay">Rp{{ number_format($order->total, 0, '', '.') }}</span>
    </div>

    <button type="submit" id="updateButton" class="btn btn-primary w-100 d-none">Update Order</button>

</form>

@if ($order->status == 'completed')
<button id="guestCompleteBtn" class="btn btn-success w-100 mt-2">Complete Order</button>
@else
<form id="guestCancelForm" action="{{ route('user.order.guestCancel', $order->id) }}" method="POST" class="mt-2" onsubmit="clearGuestStorage()">
    @csrf
    @method('PATCH')
    <button type="submit" class="btn btn-danger w-100">Cancel Order</button>
</form>
@endif

@else
Order not found.
@endif

<script>
document.querySelectorAll('.item-card').forEach(card => {
    const minus = card.querySelector('.minus');
    const plus = card.querySelector('.plus');
    const qtyText = card.querySelector('.quantity');
    const qtyInput = card.querySelector('.qtyInput');
    const subtotalDiv = card.querySelector('.subtotal');
    const price = parseInt(subtotalDiv.dataset.price);

    function recalc() {
        const qty = parseInt(qtyText.innerText);
        qtyInput.value = qty;
        subtotalDiv.innerText = "Subtotal. Rp" + (qty * price).toLocaleString('id-ID');
        document.getElementById('updateButton').classList.remove('d-none');
        updateTotal();
    }

    minus.addEventListener('click', () => {
        let qty = parseInt(qtyText.innerText);
        if (qty > 1) qty--;
        qtyText.innerText = qty;
        recalc();
    });

    plus.addEventListener('click', () => {
        let qty = parseInt(qtyText.innerText);
        qty++;
        qtyText.innerText = qty;
        recalc();
    });
});


function updateTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(sub => {
        const text = sub.innerText.replace(/\D/g, '');
        total += parseInt(text);
    });

    document.getElementById('totalDisplay').innerText = "Rp" + total.toLocaleString('id-ID');
}
function clearGuestStorage() {
localStorage.removeItem('guest_order_code');
localStorage.removeItem('cart');
localStorage.removeItem('submitted');
return true;
}
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('guestCompleteBtn');
    if (!btn) return;

    btn.addEventListener('click', () => {
        localStorage.removeItem('guest_order_code');
        localStorage.removeItem('cart');

        window.location.href = "{{ route('user.home') }}";
    });
});

</script>

@endsection
