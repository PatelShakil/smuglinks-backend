<?php

namespace App\Http\Controllers;

use App\Models\PlanPricing;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{

    public function getAllSubscriptions(Request $request)
    {

        $plans = SubscriptionPlan::with('prices')->get();

        if (count($plans) == 0) {
            return response()->json([
                "status" => false,
                "message" => "Plans are not available",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Plans Loaded Successfully",
            "data" => $plans
        ]);
    }

    public function addPlan(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            "type" => "required|string",
            "name" => "required|string",
            "description" => "required|string",
            "prices" => "required|array",
            "duration" => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first(),
                "data" => null
            ]);
        }

        // Create a new Subscription Plan
        $sp = new SubscriptionPlan();
        $sp->type = $request->type;
        $sp->name = $request->name;
        $sp->description = $request->description;
        $sp->duration = $request->duration;
        $sp->save();

        // Decode and iterate through prices
        $prices = $request->prices;  // Laravel automatically decodes JSON in array format
        foreach ($prices as $p) {
            if (isset($p['country_code']) && isset($p['amount'])) {
                $ab = new PlanPricing();
                $ab->plan_id = $sp->id;
                $ab->country_code = $p['country_code'];
                $ab->amount = $p['amount'];
                $ab->save();
            }
        }

        return response()->json([
            "status" => true,
            "message" => "Plan Created Successfully",
            "data" => $sp,
            "prices" => $prices
        ]);
    }


    public function subscribePlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "plan_id" => "required"
        ]);

        if ($validator->fails()) {
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
        $usp->razorpay_payment_id = $request->razorpay_payment_id;

        $usp->save();

        return response()->json([
            'status' => true,
            'message' => "Subscribed Plan Successfully",
            'data' => $usp
        ]);
    }

    public function addPrice(Request $request){
        $validator = Validator::make($request->all(), [
            "plan_id" => "required",
            "country_code" => "required|string",
            "amount" => "required|numeric"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first(),
                "data" => null
            ]);
        }

        $ab = new PlanPricing();
        $ab->plan_id = $request->plan_id;
        $ab->country_code = $request->country_code;
        $ab->amount = $request->amount;
        $ab->save();

        return response()->json([
            "status" => true,
            "message" => "Price Added Successfully",
            "data" => $ab
        ]);
    }

    public function updatePrice(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "country_code" => "required|string",
            "amount" => "required|numeric"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first(),
                "data" => null
            ]);
        }

        $ab = PlanPricing::find($request->id);
        $ab->country_code = $request->country_code;
        $ab->amount = $request->amount;
        $ab->save();

        return response()->json([
            "status" => true,
            "message" => "Price Updated Successfully",
            "data" => $ab
        ]);
    }

    public function deletePrice($id){
        $ab = PlanPricing::find($id);
        $ab->delete();

        return response()->json([
            "status" => true,
            "message" => "Price Deleted Successfully",
            "data" => $ab
        ]);
    }
}
