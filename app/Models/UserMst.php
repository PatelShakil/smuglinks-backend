<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMst extends Model
{
    use HasFactory;

    protected $table = 'users_mst';
    protected $primaryKey = 'uid';
    public $incrementing = false;

    protected $fillable = [
        'uid', 'username','name', 'email', 'profile', 'active'
    ];

    public function settings()
    {
        return $this->hasOne(UserSetting::class, 'uid', 'uid');
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'uid', 'uid');
    }

    public function webConfig()
    {
        return $this->hasOne(WebConfig::class, 'uid', 'uid');
    }

    public function products()
    {
        return $this->hasMany(ProductMst::class, 'uid', 'uid');
    }
}
