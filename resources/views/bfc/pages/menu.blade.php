@extends('bfc.layouts.app')
@section('title', 'Menu')
@section('content')
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
