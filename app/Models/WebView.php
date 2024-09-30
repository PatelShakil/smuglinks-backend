<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebView extends Model
{
    use HasFactory;
    protected $tableName = "web_view";
    protected $fillable =[
        'config_id',
        'ip_address'
    ];

    public function config()
    {
        return $this->belongsTo(WebConfig::class, 'config_id', 'id');
    }
}
