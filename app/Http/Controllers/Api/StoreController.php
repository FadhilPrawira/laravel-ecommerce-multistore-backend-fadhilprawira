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

    // Get seller that is currently live streaming
    public function liveStreaming(Request $request)
    {
        // Get the seller that is currently live streaming
        $stores = User::where('role', 'seller')->where('is_livestreaming', true)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of all stores that are currently live streaming',
            'data' => $stores,
        ])->setStatusCode(200);
    }
}
