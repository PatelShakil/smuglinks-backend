<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function checkUserExists(Request $request, $username)
    {
        if ($username != "") {
            return response()->json([
                "status" => true,
                "data" => UserMst::where("username", $username)->exists()
            ]);
        } else {
            return response()->json([
                "status" => false,
                "data" => "Please enter valid username"
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
}
