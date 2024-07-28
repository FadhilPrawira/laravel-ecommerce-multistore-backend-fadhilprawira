<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Eager loading (with) to get user data from product
        $products = Product::with('seller')
            ->where('seller_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();


        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found'
            ])->setStatusCode(404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'All products found',
            'data' => $products
        ])->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'string',
            'price' => 'required',
            'stock' => 'required|integer',
            'is_active' => 'required|boolean',
            'image' => 'required|image',
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Store the image in variable
        $product_image = $request->file('image');

        // Set the image name based on epoch time and extension based on MIME type
        // TODO: change the name to the hashName
        $product_image_filename = time() . '.' . $product_image->extension();

        // Store the image in the storage
        $product_image->storeAs('public/products', $product_image_filename);

        // http://localhost:8000/storage/images/YOUR_IMAGE_NAME.EXTENSION

        // Get all request data
        $data = $request->all();

        // Create a new product
        $product = new Product();
        $product->seller_id = $user->id;
        // Set the product data
        $product->category_id = $data['category_id'];
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->is_active = $data['is_active'];
        // Update the product image path in database
        $product->image = $product_image_filename;

        // Save the product
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ])->setStatusCode(201);
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
        // Validate the request
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string',
            'description' => 'string',
            'price' => 'required',
            'stock' => 'required|integer',
            // 'is_active' => 'required|boolean',
            'image' => 'image',
        ]);

        // Search the product by id
        $product = Product::find($id);

        // If the product is not found
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ])->setStatusCode(404);
        }

        // Check if the form-data request has 'image' as key
        if ($request->hasFile('image')) {

            // Store the image in variable
            $product_image = $request->file('image');

            // Set the image name based on epoch time and extension based on MIME type
            // TODO: change the name to the hashName
            $product_image_filename = time() . '.' . $product_image->extension();

            // Delete the old image if it exists
            if ($product->image) {
                // path to the image
                $old_image = 'public/products/' . $product->image;
                Storage::delete($old_image);
            }

            // Store the new image in the storage
            $product_image->storeAs('public/products', $product_image_filename);

            // http://localhost:8000/storage/images/YOUR_IMAGE_NAME.EXTENSION

        } else {
            // If the request does not have 'image' key
            // Set the product image to the old image
            $product_image_filename = $product->image;
        }

        // Get all request data
        $data = $request->all();

        // Update the product
        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            // 'is_active' => $data['is_active'],
            'image' => $product_image_filename
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ])->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Search the product by id
        $product = Product::find($id);

        // If the product is not found
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ])->setStatusCode(404);
        }
        // Product found
        // Delete the product image
        Storage::delete('public/products/' . $product->image);

        // Delete the product
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ])->setStatusCode(200);
    }
}
