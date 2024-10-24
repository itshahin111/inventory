<?php

namespace App\Http\Controllers;

use File;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function getCategories(Request $request)
    {
        // Get user ID from the header
        $userId = $request->header('id');
        $user = User::find($userId);

        // Ensure categories are not null
        $categories = $user->categories ?? [];

        return $categories;
    }

    function ProductPage(Request $request)
    {
        $categories = $this->getCategories($request);
        $userId = $request->header('id');
        return view('pages.components.product', compact('categories', 'userId'));
    }

    function addProduct(Request $request)
    {
        $user_id = $request->header('id');

        // Validate the form data
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'unit' => 'required',
            'img' => 'required|image',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Prepare the file for upload
        $img = $request->file('img');
        $t = time();
        $file_name = $img->getClientOriginalName();
        $img_name = "{$user_id}-{$t}-{$file_name}";
        $img_url = "uploads/{$img_name}";
        $img->move(public_path('uploads'), $img_name);

        // Save the product to the database
        Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'img_url' => $img_url,
            'category_id' => $request->input('category_id'),
            'user_id' => $user_id
        ]);

        return response()->json(['message' => 'Product added successfully']);
    }

    function deleteProduct(Request $request, $id)
    {
        $user_id = $request->header('id'); // Get the authenticated user's ID

        $product = Product::where('id', $id)->where('user_id', $user_id)->first();

        if ($product) {
            $filePath = public_path($product->img_url);
            if (File::exists($filePath)) {
                File::delete($filePath); // Delete the image file from the server
            }

            $product->delete();

            // Return success response
            return response()->json(['message' => 'Product deleted successfully'], 200); // OK
        }

        // If the product is not found or doesn't belong to the user
        return response()->json(['message' => 'Product not found or unauthorized'], 404); // Not Found
    }

    function productById(Request $request, $id)
    {
        $user_id = $request->header('id');
        \Log::info("Fetching product for user_id: $user_id and product_id: $id");

        $product = Product::where('id', $id)->where('user_id', $user_id)->first();

        if (!$product) {
            \Log::warning("Product not found for user_id: $user_id and product_id: $id");
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    function productList(Request $request)
    {
        $user_id = $request->header('id');
        $products = Product::where('user_id', $user_id)->get();
        return response()->json($products);
    }

    function updateProduct(Request $request, $id)
    {
        $user_id = $request->header('id');

        // Find the product
        $product = Product::where('id', $id)->where('user_id', $user_id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Validate input
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'unit' => 'required',
            'category_id' => 'required|exists:categories,id',
            'img' => 'nullable|image', // Image is optional during update
        ]);

        // Prepare the data to update
        $data = [
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'category_id' => $request->input('category_id'),
        ];

        // Handle image upload if exists
        if ($request->hasFile('img')) {
            // Delete old image
            $filePath = public_path($product->img_url);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Upload new image
            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$user_id}-{$t}-{$file_name}";
            $img_url = "uploads/{$img_name}";
            $img->move(public_path('uploads'), $img_name);
            $data['img_url'] = $img_url; // Update the image URL
        }

        // Update the product
        $product->update($data);

        return response()->json(['message' => 'Product updated successfully']);
    }
}