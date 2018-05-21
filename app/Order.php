<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table='orders';
    protected $fillable=['id','pay_id','delivery_id','client_id','status_id','price'];
}
