@extends('layouts.app')
@section('title', 'Edit product')
@section('content')

<a href="{{ route('products.index') }}" class="btn btn-danger mb-3">Back</a>

<div class="card">
    <div class="card-header">Edit product</div>
    <div class="card-body">

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $product->name) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"
                >{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="500" name="price" class="form-control"
                           value="{{ old('price', $product->price) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control"
                       value="{{ old('stock', $product->stock) }}" placeholder="Input product's stock">
            </div>

            <div class="mb-3">
                <label class="form-label">Existing images</label>

                @if(!empty($product->images))
                    <div class="row">
                        @foreach($product->images as $index => $img)
                            <div class="col-3 mb-3">
                                <img src="{{ url('storage/'.$img) }}" class="img-fluid rounded border">
                                <div class="form-check mt-2">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           name="remove_images[]"
                                           value="{{ $index }}">
                                    <label class="form-check-label">Remove</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No images</p>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Add new images</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>

        </form>

    </div>
</div>

@endsection
