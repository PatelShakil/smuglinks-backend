<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function subscribePlan(Request $request){
        $validator = Validator::make($request->all(),[
            "plan_id"=>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first(),
                "data" => null
            ]);
        }

        $usp = new UserSubscription();
        $usp->plan_id = $request->plan_id;
        $usp->order_id = Str::random(32);
        $usp->enabled = true;
        $usp->start_timestamp = now();
        $usp->uid = $request->header('uid');

        $usp->save();

        return response()->json([
            'status'=>true,
            'message'=> "Subscribed Plan Successfully",
            'data'=>$usp
        ]);

    }


}
