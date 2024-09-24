<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WebConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebButtonController extends Controller
{
    public function setButton(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'btn_type' => "required",
            'btn_border_type' => "required",
            'btn_curve_type' => "required",
            'btn_font_color' => "required",
            'btn_color' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'data' => null,
                'status' => false
            ]);
        }

        $webConfig = WebConfig::where("uid", $request->header("uid"))->first();

        if ($webConfig == null) {
            return response()->json([
                'message' => "Web Config not found",
                'data' => null,
                'status' => false
            ]);
        }

        $webConfig->btn_type = $request->btn_type;
        $webConfig->btn_border_type = $request->btn_border_type;
        $webConfig->btn_curve_type = $request->btn_curve_type;
        $webConfig->btn_font_color = $request->btn_font_color;
        $webConfig->btn_color = $request->btn_color;

        $webConfig->save();

        return response()->json([
            "message" => "Button updated",
            "data" => $webConfig,
            "status" => true
        ]);
    }
}
