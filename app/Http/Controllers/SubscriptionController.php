<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{

    public function getAllSubscriptions(Request $request){

        $plans = SubscriptionPlan::get();

        if(count($plans) == 0){
            return response()->json([
                "status" => false,
                "message" => "Plans are not available",
                "data" => null
            ]);
        }

        return response()->json([
            "status"=>true,
            "message"=>"Plans Loaded Successfully",
            "data"=>$plans
        ]);
    }

    public function addPlan(Request $request){
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            "type" => "required",
            "name" => "required",
            "description"=>"required",
            "price"=>"required",
            "duration"=>"required"
        ]);  

        if($validator->fails()){
            return response()->json([
                "status" => false,
                "message"=>$validator->errors()->first(),
                "data"=>null
            ]);
        }

        $sp = new SubscriptionPlan();
        $sp->type = $request->type;
        $sp->name = $request->name;
        $sp->description = $request->description;
        $sp->price= $request->price;
        $sp->duration= $request->duration;

        $sp->save();

        return response()->json([
            "status"=>true,
            "message"=>"Plan Created Successfully",
            "data"=>$sp
        ]);
        









}
}
