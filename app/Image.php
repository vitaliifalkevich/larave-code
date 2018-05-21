<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table='images';
    protected $fillable = ['id','image', 'product_id', 'index_image_id'];
}
