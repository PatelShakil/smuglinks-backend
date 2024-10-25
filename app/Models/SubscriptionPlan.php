<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'type', 'name', 'description', 'price', 'duration','razorpay_payment_id'
    ];

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id', 'id');
    }
}
