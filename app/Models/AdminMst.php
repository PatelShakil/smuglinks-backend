<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMst extends Model
{
    use HasFactory;
    protected $table = 'admin_mst';
    protected $fillable = [
        'id',
        'name',
        'password',
        'email',
        'uid'
    ];


    
}
