<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebTheme extends Model
{
    use HasFactory;

    protected $table = 'web_themes';

    protected $fillable = [
        'type', 'img', 'name', 'description', 'enabled'
    ];

    public function webConfigs()
    {
        return $this->hasMany(WebConfig::class, 'theme_id', 'id');
    }
}
