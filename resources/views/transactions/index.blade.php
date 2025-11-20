@extends('layouts.app')
@section('title', 'Transactions')
@section('content')

@if (Auth::user()->role == 'cashier')
<a href="{{ route('transactions.create') }}" class="btn btn-success mb-3">Add</a>
@endif

<form action="{{ route('transactions.laporan') }}" method="POST">
    @csrf
    <input type="date" class="form-control mb-3" name="start" style="width: 10rem;">
    <input type="date" class="form-control mb-3" name="end" style="width: 10rem;">
    <button type="submit" class="btn btn-danger mb-3">Generate Laporan</button>
</form>

<div class="card">
    <div class="card-header">Transactions</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Detail Order</th>
                    <th>Total Amount</th>
                    <th>Paid</th>
                    <th>Change</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $i)
                @php
                    $total = $i->order->detailOrders->sum(fn($d) => $d->product->price * $d->quantity);
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $i->created_at }}</td>
                    <td>{{ $i->order->customer_name }}</td>

                    <td>
                        @foreach ($i->order->detailOrders as $j)
                        <div>
                            {{ $j->product->name }} ({{ $j->quantity }})
                            (Rp{{ number_format($j->product->price * $j->quantity, 0, ',', '.') }})
                        </div>
                        @endforeach
                    </td>

                    <td>Rp{{ number_format($total, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($i->amount_paid, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($i->change, 0, ',', '.') }}</td>

                    <td class="d-flex flex-wrap" style="gap: 0.5rem;">
                        <form action="{{ route('transactions.generateReceipt', $i->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Print</button>
                        </form>
                        <a href="{{ route('transactions.show', $i->id) }}" class="btn btn-primary">Show</a>

                        @if (Auth::user()->role == 'cashier')
                        <a href="{{ route('transactions.edit', $i->id) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('transactions.destroy', $i->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
