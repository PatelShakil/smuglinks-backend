<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserMst;
use App\Models\UserSetting;
use App\Models\WebConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function doLogin(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
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

        // Find the user by email
        $user = UserMst::where("email", $request->email)->first();

        // Check if user exists and the password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Check if the user is active
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
            $webConfig =new WebConfig();
            $webConfig->uid = $user->uid;
            $webConfig->save();

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

    public function resetPassword(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            "current_password" => "required|min:8",
            "new_password" => "required|min:8" // 'confirmed' ensures password_confirmation is provided and matches
        ]);

        // If validation fails, return the first error
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first(),
                "data" => null
            ]);
        }

        // Retrieve the uid from the request header
        $uid = $request->header('uid');

        if (!$uid) {
            return response()->json([
                "status" => false,
                "message" => "UID is required in the header",
                "data" => null
            ]);
        }

        // Find the user by UID
        $user = UserMst::where('uid', $uid)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found",
                "data" => null
            ]);
        }

        // Verify the current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                "status" => false,
                "message" => "Current password is incorrect",
                "data" => null
            ]);
        }

        // Update the password with the new one (hashed)
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Password has been reset successfully",
            "data" => null
        ]);
    }


}
