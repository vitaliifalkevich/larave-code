<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $table='products_sizes';
    protected $fillable = ['id', 'sizes_id', 'products_id'];
}
