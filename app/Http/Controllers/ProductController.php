<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('name')->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'image|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $imagePaths = [];
        if($request->hasFile('images')) {
            foreach($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        } else {
            $imagePaths = null;
        }
        $baseSlug = Str::slug($request->name);
        $counter = 1;
        $slug = '';
        while(Product::where('slug', $baseSlug)->exists()){
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        Product::create([
            'name' => $request->input('name'),
            'slug' => $slug,
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'images' => $imagePaths,
        ]);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $orders = $product->orders()->doesntHave('transaction')->paginate(10);
        return view('products.show', compact('product', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'image|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $imagePaths = $product->images ?? [];
        if($request->has('remove_images')){
            foreach($request->remove_images as $index) {
                if((isset($imagePaths[$index]))) {
                    Storage::disk('public')->delete($imagePaths[$index]);
                    unset($imagePaths[$index]);
                }
            }
            $imagePaths = array_values($imagePaths);
        }
        if($request->hasFile('images')) {
            foreach($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }
        $baseSlug = Str::slug($request->name);
        $counter = 1;
        $slug = '';
        while(Product::where('slug', $baseSlug)->exists()){
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $product->update([
            'name' => $request->input('name'),
            'slug' => $slug,
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'images' => $imagePaths,
        ]);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if($product->images) {
            foreach($product->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
