@extends('bfc.layouts.app')
@section('title', $product->name)
@section('content')

<div class="card mb-4">
    <div class="card-body">

        <div class="row g-4">

            <!-- Product Images -->
            <div class="col-12 col-lg-6">

                @if($product->images && count($product->images) > 0)
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-inner" style="height:340px;">
                            @foreach($product->images as $key => $img)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <img src="{{ asset('storage/' . $img) }}"
                                             class="d-block"
                                             style="max-height:100%; max-width:100%; object-fit:contain;">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(count($product->images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif

                    </div>
                @else
                    <div class="d-flex justify-content-center align-items-center" style="height:340px;">
                        <img src="{{ asset('images/no-image.png') }}"
                             style="max-height:100%; max-width:100%; object-fit:contain;">
                    </div>
                @endif

            </div>

            <!-- Product Info -->
            <div class="col-12 col-lg-6 d-flex flex-column justify-content-between">

                <div>
                    <h4 class="fw-bold">{{ $product->name }}</h4>

                    @if($product->description)
                        <p class="text-muted" style="font-size:14px;">
                            {{ $product->description }}
                        </p>
                    @endif

                    <p class="fw-bold" style="font-size:20px;">
                        Rp{{ number_format($product->price,0,',','.') }}
                    </p>
                    
                </div>

                <div>
                    <p class="mb-2"><strong>Stock:</strong> {{ $product->stock }}</p>

                    <button type="button"
                            class="btn btn-sm w-100 cartBtn"
                            data-product="{{ $product->id }}">
                        Loading...
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const buttons = document.querySelectorAll('.cartBtn');

    function loadCartState() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];

        buttons.forEach(btn => {
            const id = parseInt(btn.dataset.product);
            const exists = cart.some(item => item.product_id === id);

            if (exists) {
                btn.innerText = "Remove from Cart";
                btn.classList.remove("btn-success");
                btn.classList.add("btn-danger");
            } else {
                btn.innerText = "Add to Cart";
                btn.classList.remove("btn-danger");
                btn.classList.add("btn-success");
            }
        });
    }

    loadCartState();

    buttons.forEach(btn => {
        btn.addEventListener("click", function () {
            const id = parseInt(this.dataset.product);
            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            const index = cart.findIndex(item => item.product_id === id);

            if (index !== -1) {
                cart.splice(index, 1);
            } else {
                cart.push({ product_id: id, quantity: 1 });
            }

            localStorage.setItem("cart", JSON.stringify(cart));
            loadCartState();
        });
    });

});
</script>

@endsection
