@extends('layouts.app')
@section('title', 'Add a new transaction')

@section('content')
<a href="{{ route('transactions.index') }}" class="btn btn-danger mb-3">Back</a>

<div class="card">
    <div class="card-header">Add a new transaction</div>
    <div class="card-body">

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Order</label>
                <select name="order_id" class="form-select">
                    <option value="" selected disabled>--- Select Order ---</option>
                    @foreach ($orders as $order)
                        @php
                            $total = $order->detailOrders->sum(fn($d) => $d->quantity * $d->product->price);
                        @endphp
                        <option value="{{ $order->id }}" data-total="{{ $total }}">
                            Order #{{ $order->id }} - Rp{{ number_format($total, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Total</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" id="total" class="form-control" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Pay</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" id="amount_paid" name="amount_paid" class="form-control" placeholder="Input pay">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Change</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" id="change" name="change" class="form-control" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary" disabled>Submit</button>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const orderSelect = document.querySelector('select[name="order_id"]');
    const totalInput = document.getElementById('total');
    const amountPaid = document.getElementById('amount_paid');
    const changeInput = document.getElementById('change');
    const submitBtn = document.getElementById('submitBtn');

    orderSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const total = parseFloat(selected.dataset.total || 0);
        totalInput.value = total;
        calculateChange();
    });

    amountPaid.addEventListener('input', calculateChange);

    function calculateChange() {
        const total = parseFloat(totalInput.value) || 0;
        const paid = parseFloat(amountPaid.value) || 0;

        const diff = paid - total;
        changeInput.value = diff >= 0 ? diff : 0;

        submitBtn.disabled = !(paid >= total && total > 0);
    }
});
</script>

@endsection
