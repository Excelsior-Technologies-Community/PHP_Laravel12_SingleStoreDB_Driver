<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ✅ INDEX (GET all)
  public function index(Request $request)
{
    $query = Product::query();

    // search
    if ($request->search) {
        $query->where('name', 'LIKE', "%{$request->search}%");
    }

    // price filter
    if ($request->min_price && $request->max_price) {
        $query->whereBetween('price', [$request->min_price, $request->max_price]);
    }

    // sorting
    if ($request->sort_by && $request->sort_order) {
        $query->orderBy($request->sort_by, $request->sort_order);
    }

    // pagination
    $products = $query->paginate($request->limit ?? 10);

    return response()->json($products);
}

    // ✅ STORE (POST)
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:1',
    ]);

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