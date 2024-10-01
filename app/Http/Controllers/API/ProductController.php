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
        // Validate the input fields
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'action' => 'required',
            'link' => 'required',
            'btn_name' => 'required',
            'images' => 'required', // Ensure that images are provided
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
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

        // Create a new product
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
        $p->save();

        // Log to check if files exist in request
        if (!$request->hasFile('images')) {
            return response()->json([
                "status" => false,
                "message" => "No images found in the request."
            ]);
        }

        // Process and save each image
        foreach ($request->file('images') as $image) {
            // Debugging: Log the file details
            Log::info('Processing image: ' . $image->getClientOriginalName());

            // Generate unique filename
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the public folder
            try {
                $imagePath = $image->move(public_path('products'), $imageName);

                // Debugging: Check if image was saved successfully
                if (!$imagePath) {
                    Log::error('Image upload failed: ' . $image->getClientOriginalName());
                    return response()->json([
                        "status" => false,
                        "message" => "Failed to upload image: " . $image->getClientOriginalName()
                    ]);
                }

                // Save image details to the database
                ProductImage::create([
                    'product_id' => $p->id, // Associate with the product
                    'img' => '/public/storage/products/' . $imageName
                ]);
            } catch (\Exception $e) {
                // Debugging: Log any exceptions
            Log::error('Exception during image upload: ' . $e->getMessage());
                return response()->json([
                    "status" => false,
                    "message" => "An error occurred while uploading images."
                ]);
            }
        }

        return response()->json([
            "status" => true,
            "data" => $p,
            "message" => "Product created successfully with images."
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
