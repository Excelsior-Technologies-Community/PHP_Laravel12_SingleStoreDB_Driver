<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET ALL (SEARCH + FILTER + SORT + PAGINATION + STATUS FILTER)
    public function index(Request $request)
    {
        $query = Product::query();

        // search
        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        // price filter
        if ($request->min_price && $request->max_price) {
            $query->whereBetween('price', [
                $request->min_price,
                $request->max_price
            ]);
        }

        // status filter (NEW)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // sorting
        if ($request->sort_by && $request->sort_order) {
            $query->orderBy($request->sort_by, $request->sort_order);
        }

        // pagination
        $products = $query->paginate($request->limit ?? 10);

        return response()->json($products);
    }

    // CREATE PRODUCT (WITH VALIDATION)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'meta' => $request->meta ?? ['color' => 'red'],
            'status' => true
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product
        ]);
    }

    // GET SINGLE PRODUCT
    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

    // UPDATE PRODUCT
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name ?? $product->name,
            'price' => $request->price ?? $product->price,
            'meta' => $request->meta ?? $product->meta,
        ]);

        return response()->json([
            'message' => 'Product Updated',
            'data' => $product
        ]);
    }

    // DELETE PRODUCT
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Product Deleted'
        ]);
    }

    // TOGGLE STATUS (ACTIVE / INACTIVE)
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);

        $product->status = !$product->status;
        $product->save();

        return response()->json([
            'message' => 'Status Updated Successfully',
            'data' => $product
        ]);
    }

    // ANALYTICS DASHBOARD API
    public function analytics()
    {
        return response()->json([
            'total_products' => Product::count(),
            'active_products' => Product::where('status', true)->count(),
            'inactive_products' => Product::where('status', false)->count(),
            'average_price' => round(Product::avg('price'), 2),
            'max_price' => Product::max('price'),
            'min_price' => Product::min('price'),
        ]);
    }
}