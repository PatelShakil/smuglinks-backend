<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserMst;
use App\Models\UserSetting;
use App\Models\WebConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function getWebLink(Request $request){
        $validator = Validator::make($request->all(),[
            'username'=>"required|exists:users_mst,username"
        ]);
        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "message"=>$validator->errors()->first()
            ]);
        }

        //update web views
        $webConfig = WebConfig::where("uid",UserMst::where("username",$request->username)->first()->uid)->first();
        $webConfig->web_views = $webConfig->web_views + 1;
        $webConfig->save();

        $user = UserMst::where("username",$request->username)
        ->with(["links.views","products.images","subscriptions","settings","webConfig"])->first();
        return response()->json([
            "status"=>true,
            "message"=>"User Found",
            "data"=>$user
        ]);
    }

    public function checkUserExists(Request $request, $username)
    {
        if ($username != "") {
            return response()->json([
                "status" => true,
                "message" => "Response Loaded Successfully",
                "data" => UserMst::where("username", $username)->exists()
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please enter valid username",
                "data" => null
            ]);
        }
    }

    public function getUserDetails(Request $request)
    {
        $user = UserMst::where("uid", $request->header("uid"))
            ->with('settings')
            ->with('webConfig')
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

    public function update(Request $request)
    {
        $user = UserMst::where("uid", $request->header("uid"))->first();
        if ($user == null) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => "User Not Found"
            ]);
        }

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "category" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ]);
        }

        $user->name = $request->name;
        $setting = UserSetting::where("uid", $user->uid)->first();
        if ($setting != null) {
            $setting->category = $request->category;
        } else {
            $setting = new UserSetting();
            $setting->uid = $user->uid;
            $setting->category = $request->category;
        }
        $user->save();
        $setting->save();
        return response()->json([
            "status" => true,
            "data" => $user,
            "message" => "User Details Updated Successfully"
        ]);
    }

    public function addProfileImage(Request $request)
    {
        // $validator = Validator::make($request->file(),[
        //     'image' => 'required|image|max:2048', // Validate the image file
        // ]);

        if ($request->file('image') == null) {
            return response()->json([
                "message" => "Image is Required",
                "status" => false,
                "data" => null
            ]);
        }


        $imagePath = $request->file('image')->store('public/profiles');
        $user = UserMst::where("uid", $request->header("uid"))->first();
        if ($user) {

            if ($user->profile) {
                $filePath = "/home/u533961363/domains/api.smuglinks.com/public_html/public/storage/profiles/" . basename($user->profile);
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
        } else {
            return response()->json([
                "message" => "User Not Found",
                "status" => false,
                "data" => null
            ]);
        }
    }

    public function deleteProfileImage(Request $request)
    {
        $user = UserMst::where("uid", $request->header("uid"))->first();
        if ($user) {
            if ($user->profile) {
                $filePath = "/home/u533961363/domains/api.smuglinks.com/public_html/public/storage/profiles/" . basename($user->profile);
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }

                $user->profile = null;
                $user->save();
                return response()->json([
                    "message" => "Profile Updated successfully",
                    "status" => true,
                    "data" => []
                ]);
            } else {
                return response()->json([
                    "message" => "Profile Image Not exists",
                    "status" => false,
                    "data" => []
                ]);
            }
        } else {
            return response()->json([
                "message" => "User Not Found",
                "status" => false,
                "data" => null
            ]);
        }
    }

    public function addTitleBio(Request $request){
        $validator = Validator::make($request->all(),[
            "title" => "required",
            "bio" =>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "message"=>$validator->errors()->first(),
                "data"=>null
            ]);
        }

        $user = UserSetting::where("uid",$request->header("uid"))->first();

        if($user){
            $user->title = $request->title;
            $user->bio = $request->bio;
            $user->save();
            return response()->json([
                "status"=>true,
                "message"=>"Title and Bio updated !!!",
                "data" => $user
            ]);
        }else{
            return response()->json([
                "status" => false,
                "message" => "User Not Found",
                "data" => null
            ]);
        }



    }

    public function updateTabName(Request $request){
        $validator = Validator::make($request->all(),[
            "tab_name" => "required"
        ]);

        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "message"=>$validator->errors()->first(),
                "data"=>null
            ]);
        }

        $user = UserSetting::where("uid",$request->header("uid"))->first();

        if($user){
            $user->tab_name = $request->tab_name;
            $user->save();
            return response()->json([
                "status"=>true,
                "message"=>"Tab Name updated !!!",
                "data" => $user
            ]);
        }else{
            return response()->json([
                "status" => false,
                "message" => "User Not Found",
                "data" => null
            ]);
        }
    }

}
