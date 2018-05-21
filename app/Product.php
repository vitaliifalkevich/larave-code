<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['id', 'productName', 'description', 'price', 'category_id','alias'];

    public function getCategory()
    {
        return $this->hasMany('App\Category', 'id', 'category_id');
    }
    /*Выбрать все цвета конкретного товара*/
    public function getColorsForProduct() {
        return $this->belongsToMany('App\Color','products_colors','products_id','colors_id');
    }
    public function getSizesForProduct() {
        return $this->belongsToMany('App\Size','products_sizes','products_id','sizes_id');
    }

}
