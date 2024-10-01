<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\ProductMst;
use Illuminate\Http\Request;
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

        // Process and store multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate unique filename and store the image in the public folder
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);

                // Store the image URL in the `products_images` table
                ProductImage::create([
                    'product_id' => $p->id, // Associate with the product
                    'img' => '/uploads/products/' . $imageName
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

        if(count($products) > 0){
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
