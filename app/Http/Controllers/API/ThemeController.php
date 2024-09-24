<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WebConfig;
use App\Models\WebTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{
    public function getAllTheme()
    {
        $data = WebTheme::where("enabled", true)->get();
        if ($data != null) {
            return response()->json([
                "message" => "Theme Loaded successfully",
                "status" => true,
                "data" => $data
            ]);
        } else {
            return response()->json([
                "message" => "Theme Not Found",
                "status" => false,
                "data" => null
            ]);
        }
    }

    public function selectTheme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first(),
                "status" => false,
                "data" => null
            ]);
        }

        $webConfig = WebConfig::where("uid", $request->header("uid"))->first();

        if ($webConfig != null) {
            $webConfig->theme_id = $request->id;
            $webConfig->save();
            return response()->json([
                'message' => "Theme selected successfully",
                'status' => true,
                'data' => null
            ]);
        }else{
            return response()->json([
                'message' => "Config not available",
                'status' => false,
                'data' => null
            ]);

        }
    }
}
