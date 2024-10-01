<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMst extends Model
{
    use HasFactory;
    protected $table = "products_mst";
    protected $fillable = [
        'id','uid','name','description','category','action','link','btn_name'
    ];

    public function images(){
        return $this->hasMany(ProductImage::class,'product_id','id');
    }


}
