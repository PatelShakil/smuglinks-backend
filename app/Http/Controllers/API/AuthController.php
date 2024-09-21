<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserMst;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required",
            "password"=>"required|min:8"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ]);
        }

        $user = UserMst::where("email", $request->email)
            ->where("password", $request->password)
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

    public function doSignup(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            "email" => "email|required|unique:users_mst,email",  // Ensure email is unique in users_mst table
            "username" => "required",
            "password" => "required|min:8"
        ]);

        // If validation fails, return the first error
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ]);
        }

        // Create a new user instance
        $user = new UserMst();
        $user->uid = Str::random(64);  // Generate a 64-character alphanumeric UID
        $user->email = $request->email;
        $user->password = Hash::make($request->password);  // Hash the password before storing
        $user->username = $request->username;

        // Start a transaction for database operations
        DB::beginTransaction();
        try {
            // Save the user details in users_mst table
            $user->save();

            // Create and save user settings in user_settings table
            $setting = new UserSetting();
            $setting->uid = $user->uid;
            $setting->save();

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                "status" => true,
                "data" => $user,
                "message" => "Account Created Successfully"
            ]);
        } catch (\Throwable $th) {
            // Rollback the transaction in case of any error
            DB::rollback();

            // Return an error response with the exception message
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $th->getMessage()
            ]);
        }
    }



}
