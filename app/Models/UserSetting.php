<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $table = 'users_settings';

    protected $fillable = [
        'uid', 'title', 'bio', 'category','tab_name'
    ];

    public function user()
    {
        return $this->belongsTo(UserMst::class, 'uid', 'uid');
    }
}
