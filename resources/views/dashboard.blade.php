@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="card mb-3">
        <div class="card-header">Dashboard</div>
        <div class="card-body">
            Hi, {{ Auth::user()->username }}!
        </div>
    </div>
    <div class="card">
        <div class="card-header">Data Counts</div>
        <div class="card-body">
            <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                    <h5 class="card-title">Products</h5>
                        <p class="card-text fs-2">{{ $productCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>
                        <p class="card-text fs-2">{{ $orderCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Transactions</h5>
                        <p class="card-text fs-2">{{ $transactionCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
