<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    
    public function addLink($request){
        $validator = Validator::make($request->all(),[
            "title"=>"string|required",
            "url"=>"string|url|required",
            "is_classic"=>"required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ]);
        }

        $uid = $request->header("uid");

        $link = new Link();
        $link->name = $request->title;
        $link->url = $request->url;
        $link->is_classic = $request->is_classic;
        try{
            DB::beginTransaction();
            $link->enabled=true;
            $link->type = "normal";
            $link->uid = $uid;
            $link->save();
            DB::commit();
            return response()->json([
                "status" => true,
                "data" => $link,
                "message" => "Link added successfully"
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => "Error occurred while adding link"
            ]);
        }
    }

}
