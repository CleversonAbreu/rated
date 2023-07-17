<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'description','created_at','updated_at'
    ];

    protected $hidden = [
       'created_at','updated_at','id'
    ];
}
