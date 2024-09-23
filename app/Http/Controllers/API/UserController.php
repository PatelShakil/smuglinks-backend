<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserMst;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function checkUserExists(Request $request, $username)
    {
        if ($username != "") {
            return response()->json([
                "status" => true,
                "message"=>"Response Loaded Successfully",
                "data" => UserMst::where("username", $username)->exists()
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" =>"Please enter valid username",
                "data" => null
            ]);
        }
    }

    public function getUserDetails(Request $request)
    {
        $user = UserMst::where("uid", $request->header("uid"))
        ->with('settings')
        ->first();

        if ($user != null) {
            if ($user->active) {
                return response()->json([
                    "status" => true,
                    "data" => $user
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "data" => "User is not active"
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "data" => "User Not Found"
            ]);
        }
    }

    public function update(Request $request){
        $user = UserMst::where("uid",$request->header("uid"))->first();
        if($user == null){
            return response()->json([
                "status"=>false,
                "data"=>null,
                "message"=>"User Not Found"
            ]);
        }

        $validator = Validator::make($request->all(),[
            "name"=>"required",
            "category"=>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "data"=>null,
                "message"=>$validator->errors()->first()
            ]);
        }

        $user->name = $request->name;
        $setting = UserSetting::where("uid",$user->uid)->first();
        if($setting != null){
            $setting->category = $request->category;
        }else{
            $setting = new UserSetting();
            $setting->uid = $user->uid;
            $setting->category = $request->category;
        }
        $user->save();
        $setting->save();
        return response()->json([
            "status"=>true,
            "data"=>$user,
            "message"=>"User Details Updated Successfully"
        ]);
    }

    public function addProfileImage(Request $request){
        // $validator = Validator::make($request->file(),[
        //     'image' => 'required|image|max:2048', // Validate the image file
        // ]);

        if($request->file('image') == null){
            return response()->json([
                "message" => "Image is Required",
                "status" => false,
                "data" => null
            ]);
        }

    
        $imagePath = $request->file('image')->store('public/images/profiles');
        $user = UserMst::where("uid",$request->header("uid"))->first();
        if($user){

            if($user->profile){
                $filePath = public_path(str_replace("public/storage","public",basename($user->profile_pic)));
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }
            }


            $user->profile = str_replace("public", "public/storage", $imagePath);
            $user->save();
            return response()->json([
                "message" => "Profile Updated successfully",
                "status" => true,
                "data" => []
            ]);
        }else{
            return response()->json([
                "message" => "User Not Found",
                "status" => false,
                "data" => null
            ]);
        }
    }

    public function deleteProfileImage(Request $request){
        
    }
}
