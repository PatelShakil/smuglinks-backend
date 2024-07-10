<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebFont extends Model
{
    use HasFactory;

    protected $table = 'web_fonts';

    protected $fillable = [
        'type', 'name', 'enabled'
    ];

    public function webConfigs()
    {
        return $this->hasMany(WebConfig::class, 'font_id', 'id');
    }
}
