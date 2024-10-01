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

        Log::info($request);

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
        $p->save(); // Save product

        // Handle image upload
        $images = $request->file('images');

        // If it's a single image, wrap it in an array to process it in the same loop
        if (!is_array($images)) {
            $images = [$images];
        }

        // Process each image
        foreach ($images as $image) {
            Log::info('Processing image: ' . $image->getClientOriginalName());

            // Generate unique filename for each image
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the public/uploads/products folder
            try {
                $image->move(public_path('uploads/products'), $imageName);

                // Save image details to the `products_images` table
                ProductImage::create([
                    'product_id' => $p->id, // Associate image with the product
                    'img' => '/uploads/products/' . $imageName
                ]);
            } catch (\Exception $e) {
                Log::error('Exception during image upload: ' . $e->getMessage());
                return response()->json([
                    "status" => false,
                    "message" => "Error uploading image: " . $image->getClientOriginalName()
                ]);
            }
        }

        return response()->json([
            "status" => true,
            "data" => $p,
            "message" => "Product and images uploaded successfully."
        ]);
    }




    public function getProducts(Request $request){
        $products = ProductMst::where("uid",$request->header('uid'))
        ->with('images')
        ->first();

        if($products != null){
            return response()->json([
                'message'=>"Products Loaded Successfully",
                'status'=>true,
                "data"=>$products
            ]);
        }else{
            return response()->json([
                'message'=>"Products not available",
                'status'=>false,
                "data"=>$products
            ]);
        }
    }

}
