<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebConfig extends Model
{
    use HasFactory;

    protected $table = 'web_config';

    protected $fillable = [
        'uid',
        'font_id',
        'font_color',
        'theme_id',
        'btn_type',
        'btn_border_type',
        'btn_curve_type',
        'btn_color',
        'bg_type',
        'bg_color',
        'start_color',
        'end_color',
        'bg_img',
        'web_views'
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

    public function views()
    {
        return $this->hasMany(WebView::class, 'config_id', 'id');
    }
}
