@extends('bfc.layouts.app')
@section('title', 'Home')
@section('content')

<div class="container py-4">
<!-- Hero -->
<section class="mb-4">
    <div class="card bg-bfc text-white border-0">
        <div class="row g-0 align-items-center">
            <div class="col-12 col-lg-7">
                <div class="card-body py-5 px-4">
                    <h1 class="display-6 fw-bold">Best Fried Chicken</h1>
                    <p class="lead mb-4">Crispy-nya beda, enaknya nyata.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.menu') }}" class="btn btn-light btn-lg">View Menu</a>
                        <a href="#" class="btn btn-outline-light btn-lg">Order Now</a>
                    </div>
                    <p class="mt-3 small">Open daily. Quick pick-up for students and staff.</p>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <img src="{{ asset('storage/hero/hero.jpg') }}" class="img-fluid rounded-end" alt="Delicious food" style="height:100%;object-fit:cover;">
            </div>
        </div>
    </div>
</section>

<!-- Why choose us -->
<section class="mb-4">
    <div class="row text-center">
        <div class="col-12 col-md-4 mb-3">
            <div class="p-3 border rounded">
                <h5 class="mb-2">Fresh daily</h5>
                <p class="mb-0 small text-muted">Ingredients prepared every morning.</p>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="p-3 border rounded">
                <h5 class="mb-2">Transparent price</h5>
                <p class="mb-0 small text-muted">Clear prices. No surprises at checkout.</p>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="p-3 border rounded">
                <h5 class="mb-2">Fast service</h5>
                <p class="mb-0 small text-muted">Ready in minutes for busy breaks.</p>
            </div>
        </div>
    </div>
</section>

<!-- Popular menu -->
<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Menu</h4>
        <a href="{{ route('user.menu') }}" class="small text-decoration-none">See full menu →</a>
    </div>

    <div class="row g-3">
        @if(isset($products) && $products->count())
            @foreach($products as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('user.show', $item->slug) }}" style="color: black; text-decoration: none;">
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
</section>

{{-- <!-- Promo -->
<section class="mb-4">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Daily Promo</h5>
                <p class="small text-muted mb-0">{{ $promo ?? 'Check the menu for today\'s deals. Discounts on combo meals.' }}</p>
            </div>
            <a href="{{ route('user.menu') }}" class="btn btn-outline-primary">View promos</a>
        </div>
    </div>
</section> --}}

<!-- Footer CTA -->
<section class="text-center mt-4">
    <p class="mb-2 small text-muted">Hungry now? Place your order and pick it up fast.</p>
    <a href="#" class="btn btn-lg btn-bfc">Order now</a>
</section>

</div>

@endsection
