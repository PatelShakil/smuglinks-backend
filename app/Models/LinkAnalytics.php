<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkAnalytics extends Model
{
    use HasFactory;

    protected $table = 'links_analytics';

    protected $fillable = [
        'link_id', 'clicks'
    ];

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id', 'id');
    }
}
