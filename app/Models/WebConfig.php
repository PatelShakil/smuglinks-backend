<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebConfig extends Model
{
    use HasFactory;

    protected $table = 'web_config';

    protected $fillable = [
        'uid', 'font_id', 'button_id', 'theme_id', 'is_gradient', 'start_color', 'end_color'
    ];

    public function user()
    {
        return $this->belongsTo(UserMst::class, 'uid', 'uid');
    }

    public function font()
    {
        return $this->belongsTo(WebFont::class, 'font_id', 'id');
    }

    public function button()
    {
        return $this->belongsTo(WebButton::class, 'button_id', 'id');
    }

    public function theme()
    {
        return $this->belongsTo(WebTheme::class, 'theme_id', 'id');
    }
}
