<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all stores
        $stores = User::where('role', 'seller')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of all stores',
            'data' => $stores,
        ])->setStatusCode(200);
    }

    // Get all products from a store
    public function productsByStore(Request $request, string $id)
    {
        // Get the products
        $products = Product::where('seller_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of all products from the store',
            'data' => $products,
        ])->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
