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
                    <option>Order #{{ $transaction->order->id }} - Rp{{ number_format($transaction->order->total_price, 0, ',', '.') }}</option>
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
                        value="{{ $transaction->total }}"
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
                        id="paid"
                        name="paid"
                        value="{{ $transaction->paid }}">
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
                        value="{{ $transaction->paid - $transaction->total }}"
                        readonly>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" disabled>Submit</button>
        </form>
    </div>
</div>

<script>
    const paidInput = document.getElementById('paid');
    const totalInput = document.getElementById('total');
    const changeInput = document.getElementById('change');
    const submitButton = document.querySelector('button[type="submit"]');

    function calculateChange() {
        const total = Number(totalInput.value);
        const paid = Number(paidInput.value);
        const change = paid - total;

        changeInput.value = change > 0 ? change : 0;
        submitButton.disabled = !(paid >= total && total > 0);
    }

    paidInput.addEventListener('input', calculateChange);
</script>

@endsection
