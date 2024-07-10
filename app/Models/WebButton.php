<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebButton extends Model
{
    use HasFactory;

    protected $table = 'web_buttons';

    protected $fillable = [
        'type', 'name', 'enabled'
    ];

    public function webConfigs()
    {
        return $this->hasMany(WebConfig::class, 'button_id', 'id');
    }
}
