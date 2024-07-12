<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserMst;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function checkUserExists(Request $request, $username)
    {
        if ($username != "") {
            return response()->json([
                "status" => true,
                "data" => UserMst::where("username", $username)->exists()
            ]);
        }else{
            return response()->json([
                "status" => false,
                "data" => "Please enter valid username"
            ]);
        }
    }
}
