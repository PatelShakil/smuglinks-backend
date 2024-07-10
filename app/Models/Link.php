<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links_mst';

    protected $fillable = [
        'uid', 'name', 'type', 'image', 'enabled'
    ];

    public function user()
    {
        return $this->belongsTo(UserMst::class, 'uid', 'uid');
    }

    public function analytics()
    {
        return $this->hasOne(LinkAnalytics::class, 'link_id', 'id');
    }
}
