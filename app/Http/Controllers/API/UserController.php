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
        $user = UserMst::where("uid", $request->header("uid"))->first();

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
}
