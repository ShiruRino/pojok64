@extends('layouts.app')
@section('title', 'Edit a transaction - ' . $transaction->id)
@section('content')

<a href="{{ route('transactions.index') }}" class="btn btn-danger mb-3">Back</a>

<div class="card">
    <div class="card-header">Edit a transaction - {{ $transaction->id }}</div>

    <div class="card-body">
        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Order</label>
                <select class="form-select" disabled>
                    <option>Order #{{ $transaction->order->id }} - Rp{{ number_format($transaction->order->total, 0, ',', '.') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Total</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input
                        type="number"
                        class="form-control"
                        id="total"
                        name="total"
                        value="{{ $transaction->order->total }}"
                        readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Pay</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input
                        type="number"
                        class="form-control"
                        id="amount_paid"
                        name="amount_paid"
                        value="{{ $transaction->amount_paid }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Change</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input
                        type="number"
                        class="form-control"
                        id="change"
                        value="{{ $transaction->amount_paid - $transaction->order->total }}"
                        readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ $transaction->payment_method == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const totalInput = document.getElementById('total');
    const amountPaid = document.getElementById('amount_paid');
    const changeInput = document.getElementById('change');
    const submitBtn = document.getElementById('submitBtn');

    amountPaid.addEventListener('input', calculateChange);

    function calculateChange() {
        const total = Number(totalInput.value) || 0;
        const paid = Number(amountPaid.value) || 0;

        const diff = paid - total;
        changeInput.value = diff >= 0 ? diff : 0;

        submitBtn.disabled = !(paid >= total && total > 0);
    }

    calculateChange();
});
</script>


@endsection
