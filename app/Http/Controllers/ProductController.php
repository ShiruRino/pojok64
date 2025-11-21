<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $imagePaths = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('products', 'public');
                }
            }

            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            Product::create([
                'name' => $request->name,
                'description' => $request->description ?? '',
                'slug' => $slug,
                'price' => $request->price,
                'stock' => $request->stock,
                'images' => $imagePaths,
            ]);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
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
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $imagePaths = $product->images ?? [];

            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $index) {
                    if (isset($imagePaths[$index])) {
                        Storage::disk('public')->delete($imagePaths[$index]);
                        unset($imagePaths[$index]);
                    }
                }
                $imagePaths = array_values($imagePaths);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('products', 'public');
                }
            }

            if ($request->name !== $product->name) {
                $slug = Str::slug($request->name);
                $originalSlug = $slug;
                $counter = 1;

                while (
                    Product::where('slug', $slug)
                    ->where('id', '!=', $product->id)
                    ->exists()
                ) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            } else {
                $slug = $product->slug;
            }

            $product->update([
                'name' => $request->name,
                'slug' => $slug,
                'price' => $request->price,
                'stock' => $request->stock,
                'description' => $request->description,
                'images' => array_values($imagePaths),
            ]);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            if ($product->images) {
                foreach ($product->images as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            $product->delete();

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
    }

}
