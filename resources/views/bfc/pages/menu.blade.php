@extends('bfc.layouts.app')
@section('title', 'Menu')
@section('content')
<div class="row g-3">
        @if(isset($products) && $products->count())
            @foreach($products as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('user.order.create', $item->slug) }}" style="color: black; text-decoration: none;">
                        <div class="card h-100">
                            <img src="{{ $item->images ? asset('storage/'.$item->images[0]) : asset('images/placeholder.png') }}"
                                 class="card-img-top" alt="{{ $item->name }}" style="height:160px;object-fit:cover;">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-1">{{ $item->name }}</h6>
                                <p class="card-text small text-muted mb-2">{{ Str::limit($item->description, 60) }}</p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <strong>Rp{{ number_format($item->price,0,',','.') }}</strong>
                                    <a href="{{ route('user.order.create', ['slug' => $item->slug]) }}" class="btn btn-sm btn-success">Order</a>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <p>No menu found</p>
        @endif
    </div>
@endsection
