<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('pay_id')->unsigned();
            $table->integer('delivery_id')->unsigned();
            $table->string('firstName',100);
            $table->string('lastName',100);
            $table->integer('phone');
            $table->text('adress');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('pay_id')->references('id')->on('way_pay');
            $table->foreign('delivery_id')->references('id')->on('way_delivery');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
