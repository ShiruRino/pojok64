@extends('layouts.app')
@section('title', 'Add a new product')
@section('content')
    <a href="{{ route('products.index') }}" class="btn btn-danger mb-3">Back</a>
    <div class="card">
        <div class="card-header">Add a new product</div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        placeholder="Input product name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        placeholder="Input product description">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input
                            type="number"
                            name="price"
                            step="500"
                            value="{{ old('price') }}"
                            class="form-control"
                            placeholder="Input product price">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input
                        type="number"
                        name="stock"
                        value="{{ old('stock') }}"
                        class="form-control"
                        placeholder="Input product stock">
                </div>

                <div class="mb-3">
                    <label class="form-label">Images</label>
                    <input
                        type="file"
                        name="images[]"
                        class="form-control"
                        multiple>
                </div>

                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
@endsection
