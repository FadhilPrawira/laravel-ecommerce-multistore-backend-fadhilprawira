<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'address_id' => 'required|integer|exists:addresses,id',
            'seller_id' => 'required|integer|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_service' => 'required|string',
        ]);

        // Get the authenticated user
        $customer = $request->user();

        $total_price = 0;
        foreach ($request->items as $item) {
            // Get the product
            $product = Product::find($item['product_id']);

            $total_price += $item['quantity'] * $product->price;
        }

        // Calculate grand total
        $grand_total = $total_price + $request->shipping_cost;

        // Create new order
        $order = Order::create([
            'customer_id' => $customer->id,
            'address_id' => $request->address_id,
            'seller_id' => $request->seller_id,
            'shipping_price' => $request->shipping_cost,
            'shipping_service' => $request->shipping_service,
            'status' => 'PENDING',
            'total_price' => $total_price,
            'grand_total' => $grand_total,
            'transaction_number' => 'TRX-' . time(),
        ]);

        foreach ($request->items as $item) {
            // Get the product
            $product = Product::find($item['product_id']);

            // If product found then create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Order has been created',
            'data' => $order
        ])->setStatusCode(201);
    }

    // Update shipping_receipt_number
    public function updateShippingReceiptNumber(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'shipping_receipt_number' => 'required|string',
        ]);

        // Get the order
        $order = Order::find($id);

        // Update the order
        $order->update([
            'shipping_receipt_number' => $request->shipping_receipt_number,
            // 'status' => 'SHIPPING',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Shipping receipt number has been updated',
            'data' => $order
        ]);
    }

    // History order for customer
    public function customerOrderHistory(Request $request)
    {
        // Get the authenticated user
        $customer = $request->user();

        // Get all orders
        $orders = Order::where('customer_id', $customer->id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of all customer\'s orders',
            'data' => $orders
        ]);
    }

    // History order for seller
    public function sellerOrderHistory(Request $request)
    {
        // Get the authenticated user
        $seller = $request->user();

        // Get all orders
        $orders = Order::where('seller_id', $seller->id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of all seller\'s orders',
            'data' => $orders
        ]);
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
