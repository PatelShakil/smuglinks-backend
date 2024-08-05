<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => $request->email
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ], 400);
        }

        $user = UserMst::where("email", $request->email)
            ->where("uid", $request->header("uid"))
            ->get()
            ->first();

        if ($user != null) {
            if ($user->active) {
                return response()->json([
                    "status" => true,
                    "message" => "Logged in successfully",
                    "data" => $user
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Your account is Suspended",
                    "data" => null
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please Enter Valid Credentials",
                "data" => null
            ]);
        }
    }

    public function doSignup(Request $request){
        $validator = Validator::make($request->all(),[
            "email"=>"email|required",
            "uid"=>"required",
            "username"=>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "data"=>null,
                "message"=>$validator->errors->first()
            ]);
        }

        $user = new UserMst();
        $user->email = $request->email;
        $user->uid = $request->uid;
        $user->username = $request->username;

        DB::beginTransaction();
        try {
            $user->save();
            DB::commit();
            return response()->json([
                "status"=>true,
                "data"=>$user,
                "message"=>"Account Created Successfully"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                "status"=>false,
                "data"=>null,
                "message"=>$th->getMessage()

            ]);
        }


    }



}
