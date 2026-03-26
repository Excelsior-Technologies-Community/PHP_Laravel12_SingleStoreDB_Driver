<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ✅ INDEX (GET all)
    public function index()
    {
        return response()->json(Product::all());
    }

    // ✅ STORE (POST)
    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'meta' => ['color' => 'red']
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product
        ]);
    }

    // ✅ SHOW (GET single)
    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

    // ✅ UPDATE (PUT)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product Updated',
            'data' => $product
        ]);
    }

    // ✅ DELETE (DELETE)
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Product Deleted'
        ]);
    }
}