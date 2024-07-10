<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'users_subscription';

    protected $fillable = [
        'uid', 'order_id', 'start_timestamp', 'enabled', 'plan_id'
    ];

    public function user()
    {
        return $this->belongsTo(UserMst::class, 'uid', 'uid');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id', 'id');
    }
}
