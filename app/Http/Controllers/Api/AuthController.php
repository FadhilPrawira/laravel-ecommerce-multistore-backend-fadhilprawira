<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Seller register
    public function sellerRegister(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string',
            'photo' => 'required|image',
        ]);

        // Store the image in variable
        $seller_image_file = $request->file('photo');

        // Set the image name based on epoch time and extension based on MIME type
        $seller_image_filename = time() . '.' . $seller_image_file->extension();

        // Store the image in the storage
        $seller_image_file->storeAs('public/images', $seller_image_filename);
        // http://localhost:8000/storage/images/YOUR_IMAGE_NAME.EXTENSION

        // Get all request data
        $data = $request->all();

        // Hash the password
        $data['password'] = Hash::make($data['password']);

        // Set the role to seller
        $data['role'] = 'seller';

        // Create a new user
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        $user->country = $data['country'];
        $user->province = $data['province'];
        $user->city = $data['city'];
        $user->district = $data['district'];
        $user->postal_code = $data['postal_code'];
        // Update the user image path in database
        $user->photo = $seller_image_filename;
        $user->role = $data['role'];

        // Save the user
        $user->save();


        return response()->json([
            'status' => 'success',
            'message' => 'Seller registered successfully',
            'data' => $user,
        ])->setStatusCode(201);
    }

    // Customer register
    public function customerRegister(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            // 'phone' => 'required|string',
        ]);

        // Get all request data
        $data = $request->all();

        // Hash the password
        $data['password'] = Hash::make($data['password']);

        // Set the role to customer
        $data['role'] = 'customer';

        // Create the user
        $user = User::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer registered successfully',
            'data' => $user,
        ])->setStatusCode(201);
    }

    // login
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
        ]);

        // Search by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and if password correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email or password is incorrect.'
            ])->setStatusCode(401);
        }

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login success',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ])->setStatusCode(200);
    }

    // Logout
    public function logout(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Revoke the token that was used to authenticate the current request
        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout success'
        ])->setStatusCode(200);
    }
}
