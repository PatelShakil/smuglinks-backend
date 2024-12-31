<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdminMst;
use App\Models\WebConfig;
use App\Models\WebFont;
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

    public function getFonts(Request $request){
        $admin = AdminMst::where("uid", request()->header("uid"))->first();
        if($admin != null){
            $data = WebFont::all();
            if ($data != null) {
                return response()->json([
                    "message" => "Fonts Loaded successfully",
                    "status" => true,
                    "data" => $data
                ]);
            } else {
                return response()->json([
                    "message" => "Fonts Not Found",
                    "status" => false,
                    "data" => null
                ]);
            }
        }
        $data = WebFont::where("enabled",true)->get();
        return response()->json([
            "message"=>"Font Loaded",
            "status"=>true,
            "data"=>$data
        ]);
    }

    public function selectFont(Request $request){
        $validator = Validator::make($request->all(),[
            "font_id"=>"required",
            "font_color"=>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "message"=>$validator->errors()->first(),
                "data"=>null,
                "status",false
            ]);
        }


        $webConfig = WebConfig::where("uid",$request->header("uid"))->first();

        $webConfig->font_id = $request->font_id;
        $webConfig->font_color = $request->font_color;

        $webConfig->save();
        return response()->json([
            "message"=>"Font updated successfully",
            "data"=>null,
            "status"=>true
        ]);
    }

    public function deleteFont($id){
        $font = WebFont::find($id);
        if($font!= null){
            $font->enabled = !$font->enabled;
            return response()->json([
                "message"=>"Font deleted successfully",
                "status"=>true,
                "data"=>null
            ]);
        }
        return response()->json([
            "message"=>"Font not found",
            "status"=>false,
            "data"=>null
        ]);
    }

    public function updateFont(Request $request){
        $validator = Validator::make($request->all(),[
            "id"=>"required",
            "type"=>"required",
            "name"=>"required",
        ]);

        if($validator->fails()){
            return response()->json([
                "message"=>$validator->errors()->first(),
                "data"=>null,
                "status"=>false
            ]);
        }

        $font = WebFont::find($request->id);
        $font->type = $request->type;
        $font->name = $request->name;
        $font->save();
        return response()->json([
            "message"=>"Font updated successfully",
            "data"=>null,
            "status"=>true
        ]);
    }

}
