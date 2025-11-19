@extends('layouts.app')
@section('title', 'Product List')
@section('content')
    <a href="{{ route('products.create') }}" class="btn btn-success mb-3">Add</a>
    <div class="card">
        <div class="card">
            <div class="card-header">Product List</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ url('storage/'.$product->images[0]) }}" alt="" width="50rem" height="50rem" style="object-fit:cover;"></td>
                                <td>{{ $product->name }}</td>
                                <td>Rp{{ number_format($product->price, 0, '', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td class="d-flex flex-wrap" style="gap: 0.5rem">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Show</a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
