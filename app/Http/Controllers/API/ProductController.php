<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\ProductMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function addProduct(Request $request)
    {
        // Validate input fields, including images
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'action' => 'required',
            'link' => 'required',
            'btn_name' => 'required',
            'images' => 'required', // Required at least one image
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image (if multiple)
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ]);
        }

        // Log request to help with debugging
        Log::info('Received product request: ', $request->all());

        // Create a new product record
        $p = new ProductMst();
        $p->uid = $request->header('uid');
        $p->name = $request->name;
        $p->description = $request->description;
        $p->category = $request->category;
        $p->action = $request->action;

        // Handle WhatsApp link generation
        if ($request->action == "whatsapp") {
            $p->link = "https://wa.me/" . $request->link;
        } else {
            $p->link = $request->link;
        }

        $p->btn_name = $request->btn_name;
        // $p->save(); // Save product

        // Handle image upload
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            // Log the number of images found
            Log::info($request->file('images'));

            // Process each image
            foreach ($images as $image) {
                // Log each image processing
                Log::info('Processing image: ' . $image->getClientOriginalName());

                // Generate unique filename for each image
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store the image in the public/uploads/products folder
                try {
                    $image->move(public_path('uploads/products'), $imageName);

                    // Save image details to the `products_images` table
                    // ProductImage::create([
                    //     'product_id' => $p->id, // Associate image with the product
                    //     'img' => '/uploads/products/' . $imageName
                    // ]);

                    // Log successful image upload
                    Log::info('Image uploaded successfully: ' . $imageName);
                } catch (\Exception $e) {
                    Log::error('Exception during image upload: ' . $e->getMessage());
                    return response()->json([
                        "status" => false,
                        "message" => "Error uploading image: " . $image->getClientOriginalName()
                    ]);
                }
            }
        } else {
            Log::warning('No images were found in the request.');
            return response()->json([
                "status" => false,
                "message" => "No images found."
            ]);
        }

        return response()->json([
            "status" => true,
            "data" => $p,
            "message" => "Product and images uploaded successfully."
        ]);
    }



    public function getProducts(Request $request)
    {
        $products = ProductMst::where("uid", $request->header('uid'))
            ->with('images')->get();

        if ($products != null) {
            return response()->json([
                'message' => "Products Loaded Successfully",
                'status' => true,
                "data" => $products
            ]);
        } else {
            return response()->json([
                'message' => "Products not available",
                'status' => false,
                "data" => $products
            ]);
        }
    }

    public function deleteProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products_mst,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false
            ]);
        }
        // Find the product
        $product = ProductMst::find($request->id);

        // Check if product exists
        if (!$product) {
            return response()->json([
                "status" => false,
                "message" => "Product not found."
            ]);
        }

        // Retrieve all associated images
        $images = $product->images;

        // Delete each image file from the server
        foreach ($images as $image) {
            $imagePath = public_path($image->img); // Get the file path

            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }

            // Delete the image record from the database
            $image->delete();
        }

        // Delete the product itself
        $product->delete();

        return response()->json([
            "status" => true,
            "message" => "Product and associated images deleted successfully."
        ]);
    }

    public function deleteProductImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products_images,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false
            ]);
        }
        // Find the image by ID
        $image = ProductImage::find($request->id);

        // Check if the image exists
        if (!$image) {
            return response()->json([
                "status" => false,
                "message" => "Image not found."
            ]);
        }

        // Get the image path
        $imagePath = public_path($image->img);

        // Delete the image file from the server
        if (file_exists($imagePath)) {
            Log::info($imagePath);
            unlink($imagePath); // Delete the image file
            $image->delete();
        }

        // Delete the image record from the database

        return response()->json([
            "status" => true,
            "message" => "Image deleted successfully."
        ]);
    }

    public function addProductImage(Request $request)
    {
        // Validate the image input
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products_mst,id',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first()
            ]);
        }

        // Find the product
        $product = ProductMst::find($request->id);

        if (!$product) {
            return response()->json([
                "status" => false,
                "message" => "Product not found."
            ]);
        }

        // Handle image upload
        $images = $request->file('images');
        if (!is_array($images)) {
            $images = [$images]; // Wrap single image as an array
        }

        // Process each image
        foreach ($images as $image) {
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image
            $image->move(public_path('uploads/products'), $imageName);

            // Save image details to `products_images`
            ProductImage::create([
                'product_id' => $product->id,
                'img' => '/uploads/products/' . $imageName
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Images added successfully."
        ]);
    }


    public function editProduct(Request $request)
    {
        // Validate the input fields
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:products_mst,id',
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'action' => 'required',
            'link' => 'required',
            'btn_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first()
            ]);
        }

        // Find the product
        $product = ProductMst::find($request->id);

        // Check if product exists
        if (!$product) {
            return response()->json([
                "status" => false,
                "message" => "Product not found."
            ]);
        }

        // Update the product fields
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->action = $request->action;

        if ($request->action == "whatsapp") {
            $product->link = "https://wa.me/" . $request->link;
        } else {
            $product->link = $request->link;
        }

        $product->btn_name = $request->btn_name;
        $product->save(); // Save the product

        return response()->json([
            "status" => true,
            "message" => "Product updated successfully.",
            "data" => $product
        ]);
    }
}
