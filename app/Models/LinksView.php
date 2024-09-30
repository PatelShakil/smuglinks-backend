<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinksView extends Model
{
    use HasFactory;
    protected $tableName = "link_view";
    protected $fillable =[
        'link_id',
        'ip_address'
    ];

    public function link(){
        return $this->belongsTo(Link::class,'link_id','id');
    }
}
