<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdminMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    public function doLogin(Request $request){
        $validator = Validator::make($request->all(),[
            "email"=>"required|email",
            "password"=>"required",
        ]);

        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "message"=>$validator->errors()->first(),
                "data"=>null
            ]);
        }

        $admin = AdminMst::where("email",$request->email)
        ->where("password",$request->password)->first();

        if($admin){
            return response()->json([
                "status"=>true,
                "message"=>"Login successful",
                "data"=>$admin
            ]);
        }else{
            return response()->json([
                "status"=>false,
                "message"=>"Invalid email or password",
                "data"=>null
            ]);
        }

    }

    public function addAdmin(Request $request){
        $validator = Validator::make($request->all(),[
            "email"=>"required|email|unique:admin_mst",
            "password"=>"required|min:8",
            "name"=>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "message"=>$validator->errors()->first(),
                "data"=>null
            ]);
        }

        $admin = new AdminMst();
        $admin->email = $request->email;
        $admin->uid = Str::random(64);
        $admin->password = $request->password;
        $admin->name = $request->name;

        $admin->save();

        return response()->json([
            "status"=>true,
            "message"=>"Admin added successfully",
            "data"=>$admin
        ]);
    }


}
