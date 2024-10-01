<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = "products_images";
    protected $fillable = [
        'id','product_id','img'
    ];

    public function product(){
        return $this->belongsTo(ProductMst::class,'product_id','id');
    }
}
