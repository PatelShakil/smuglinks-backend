<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinksView;
use App\Models\WebConfig;
use App\Models\WebView;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{

    public function addLink(Request $request){
        $validator = Validator::make($request->all(),[
            "title"=>"string|required",
            "url"=>"string|required",
            "is_classic"=>"required",
            "type"=>"string|required"
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
        $link->is_classic =  boolval($request->is_classic);

        if ($request->file('image') != null) {
            $imagePath = $request->file('image')->store('public/links');
            $link->image = str_replace("public", "public/storage", $imagePath);
        }
        try{
            DB::beginTransaction();
            $link->enabled=true;
            $link->type = $request->type;
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
                "data" => $e->getMessage(),
                "message" => "Error occurred while adding link"
            ]);
        }
    }

    public function getAllLinks(Request $request){
        $uid = $request->header('uid');

        $links = Link::where('uid', $uid)->with("views")->get();

        if($links != null){
            return response()->json([
                "status" => true,
                "data" => $links,
                "message" => "Links fetched successfully"
            ]);
        }else{
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => "No links found"
            ]);
        }

    }

    public function getAnalytics(Request $request){
        $links = Link::where("uid",$request->header('uid'))
        ->with("views")
        ->get();

        $web = WebConfig::where("uid",$request->header('uid'))
        ->with("views")
        ->first();

        return response()->json([
            "status"=>true,
            "message"=>"Analytics Loaded",
            "data"=>[
                "links"=>$links,
                "web"=>$web
            ]
            ]);
    }
    public function registerLinkClick(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            "id" => 'required|exists:links_mst,id'
        ]);

        // Check for validation failure
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ]);
        }

        $link = Link::where("id",$request->id)->first();

        // Create a new LinksView record
        $l = new LinksView();
        $l->link_id = $request->id;
        // Retrieve the client's IP address
        $l->ip_address = $request->ip(); // Using Laravel helper to get client IP
        $l->save(); // Save the new record

        // Return a success response
        return response()->json([
            "status" => true,
            "data" =>$link ,
            "message" => "Link click registered successfully."
        ]);
    }

    public function getLinkById($id){

        $link = Link::where('id',$id)->first();

        if($link!= null){
            return response()->json([
                "status" => true,
                "data" => $link,
                "message" => "Link fetched successfully"
            ]);
        }else{
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => "Link not found"
            ]);
        }
    }

    public function deleteLinkById(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => "required|exists:links_mst,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => $validator->errors()->first()
            ]);
        }

        $link = Link::find($request->id);
        $link->delete();

        if($link!= null){
            return response()->json([
                "status" => true,
                "data" => null,
                "message" => "Link Deleted successfully"
            ]);
        }else{
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => "Link not found"
            ]);
        }
    }

    public function editLink(Request $request){
        $validator = Validator::make($request->all(),[
            "id"=>"required|exists:links_mst,id",
            "title"=>"string|required",
            "url"=>"string|url|required",
            "is_classic"=>"required",
            "type"=>"string|required",
            "enabled"=>"required",
            "priority"=>"required"
        ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "data" => null,
                    "message" => $validator->errors()->first()
                ]);
            }

            $link = Link::find($request->id);
            $link->name = $request->title;
            $link->url = $request->url;
            $link->is_classic =  boolval($request->is_classic);
            $link->type = $request->type;
            $link->enabled = boolval($request->enabled);
            $link->priority = $request->priority;
            $link->save();
            return response()->json([
                "status" => true,
                "data" => $link,
                "message" => "Link updated successfully"
            ]);




    }

    public function uploadPdf(Request $request){


        if ($request->file('pdf') == null) {
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => "PDF not uploaded"
            ]);
        }


        if ($request->file('pdf') != null) {
            $pdfPath = $request->file('pdf')->store('public/uploads');
            $pdfFile = str_replace("public", "public/storage", $pdfPath);
            return response()->json([
                "status" => true,
                "data" => $pdfFile,
                "message" => "PDF uploaded successfully"
            ]);
        }else{
            return response()->json([
                "status" => false,
                "data" => null,
                "message" => "PDF not uploaded"
            ]);
        }
    }






}
