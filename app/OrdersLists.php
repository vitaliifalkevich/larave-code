<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersLists extends Model
{
    protected $table='orders_lists';
    protected $fillable=['id','order_id','size_id','color_id','product_id'];
}