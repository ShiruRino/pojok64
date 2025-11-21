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
                <img src="{{ asset('storage/hero/hero.png') }}" class="img-fluid rounded-end" alt="Delicious food" style="height:100%;object-fit:cover;">
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
                    <div class="card h-100">

                        <a href="{{ route('user.show', $item->slug) }}">
                            <img src="{{ $item->images ? asset('storage/'.$item->images[0]) : asset('images/placeholder.png') }}"
                                class="card-img-top" alt="{{ $item->name }}" style="height:160px;object-fit:cover;">
                        </a>

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1">
                                <a href="{{ route('user.show', $item->slug) }}" style="color:black;text-decoration:none;">
                                    {{ $item->name }}
                                </a>
                            </h6>

                            <p class="card-text small text-muted mb-2">
                                {{ Str::limit($item->description, 60) }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <strong>Rp{{ number_format($item->price,0,',','.') }}</strong>

                                <button
                                    type="button"
                                    class="btn btn-sm cartBtn"
                                    data-product="{{ $item->id }}"
                                >
                                    Loading...
                                </button>
                            </div>

                        </div>
                    </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const buttons = document.querySelectorAll('.cartBtn');

        function loadCartState() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            buttons.forEach(btn => {
                const id = parseInt(btn.dataset.product);
                const exists = cart.some(item => item.product_id === id);

                if (exists) {
                    btn.innerText = "Remove from Cart";
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-danger');
                } else {
                    btn.innerText = "Add to Cart";
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                }
            });
        }

        loadCartState();

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = parseInt(this.dataset.product);
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                const index = cart.findIndex(item => item.product_id === id);

                if (index !== -1) {
                    cart.splice(index, 1);
                } else {
                    cart.push({ product_id: id, quantity: 1 });
                }

                localStorage.setItem('cart', JSON.stringify(cart));
                loadCartState();
            });
        });

    });
    </script>

@endsection
