@extends('bfc.layouts.app')
@section('title', $product->name)
@section('content')

<div class="card mb-5">
    <div class="card-body">
        <div class="row g-4">
            {{-- Carousel --}}
            <div class="col-12 col-lg-6">
                @if($product->images && count($product->images) > 0)
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="height:400px;">
                        @foreach($product->images as $key => $img)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <img src="{{ asset('storage/' . $img) }}" class="d-block" style="max-height:100%; max-width:100%; object-fit: contain;" alt="Image {{ $key + 1 }}">
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
                <div style="height:400px;" class="d-flex justify-content-center align-items-center">
                    <img src="{{ asset('images/no-image.png') }}" style="max-height:100%; max-width:100%; object-fit: contain;" alt="No Image">
                </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="col-12 col-lg-6 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>{{ $product->name }}</h3>
                        <span class="fw-bold">Rp{{ number_format($product->price,0,',','.') }}</span>
                    </div>

                    @if($product->description)
                    <p>{{ $product->description }}</p>
                    @endif
                </div>

                <a href="{{ route('user.order.create', ['product_id' => $product->id]) }}" class="btn btn-bfc w-100 mt-3">
                    Checkout
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
