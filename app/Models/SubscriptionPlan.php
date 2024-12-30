<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'type', 'name', 'description','duration'
    ];

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id','id');
    }

    public function users(){
     // Cross relationship through the UserSubscription table
     return $this->hasManyThrough(
        UserMst::class,               // Final model to retrieve
        UserSubscription::class,  // Intermediate model
        'plan_id',                // Foreign key on UserSubscription table
        'uid',                     // Foreign key on User table
        'id',                     // Local key on SubscriptionPlan table
        'uid'                     // Local key on UserSubscription table
    );
    }


    public function prices(){
        return $this->hasMany(PlanPricing::class,'plan_id','id');
    }


}
