<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPricing extends Model
{
    use HasFactory;
    protected $table = "plan_pricing";
    protected $fillable = [
        'plan_id','country_code','amount'
    ];

    public function plan(){
        return $this->belongsTo(SubscriptionPlan::class,'plan_id','id');
    }
}
