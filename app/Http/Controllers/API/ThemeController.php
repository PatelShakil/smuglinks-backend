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
            $webConfig->bg_type = 0;
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


    public function setBg(Request $request){
        $validator = Validator::make($request->all(),[
            'bg_type'=>'required'
        ]
        );

        if($validator->fails()){
            return response()->json([
                'message' =>$validator->errors()->first(),
                'data'=>null,
                'status'=>false
            ]);
        }

        $bg_type = $request->bg_type;

        if($bg_type > 3 || $bg_type <= 0){
            return response()->json([
                'message' => "Enter valid bg type!!!",
                'data' => null,
                'status' => false
            ]);
        }

        $webConfig = WebConfig::where("uid",$request->header("uid"))->first();

        if($webConfig == null){
            return response()->json([
                'message' => "Web Config not found",
                'data' => null,
                'status' => false
            ]);
        }

        $webConfig->bg_type = $bg_type;

        switch ($bg_type) {
            case 1 : {
                $v = Validator::make($request->all(),[
                    "bg_color"=>'required'
                ]);

                    if ($v->fails()) {
                        return response()->json([
                            'message' => $v->errors()->first(),
                            'data' => null,
                            'status' => false
                        ]);
                    }

                $webConfig->bg_color = $request->bg_color;
                break;
            }
            case 2 : {
                $v = Validator::make($request->all(),[
                    "start_color"=>'required',
                    'end_color'=>'required'
                ]);

                    if ($v->fails()) {
                        return response()->json([
                            'message' => $v->errors()->first(),
                            'data' => null,
                            'status' => false
                        ]);
                    }

                $webConfig->start_color = $request->start_color;
                $webConfig->end_color = $request->end_color;
                break;
            }
            case 3 : {
                $v = Validator::make($request->all(),[
                    'image'=>"required|image"
                ]);

                    if ($v->fails()) {
                        return response()->json([
                            'message' => $v->errors()->first(),
                            'data' => null,
                            'status' => false
                        ]);
                    }

                    if($webConfig->bg_img){
                        $filePath = "/home/u533961363/domains/api.smuglinks.com/public_html/public/storage/bg/" . basename($webConfig->bg_img);
                        if (file_exists($filePath)) {
                            unlink($filePath); // Delete the file
                        }
                    }

                    $imagePath = $request->file('image')->store('public/bg');
                $webConfig->bg_img = str_replace("public", "public/storage", $imagePath);
                break;
            }
        }

        $webConfig->save();

        return response()->json([
            "message"=>"Background Updated Successfully",
            "data"=>null,
            "status"=>true
        ]);
    }

}
