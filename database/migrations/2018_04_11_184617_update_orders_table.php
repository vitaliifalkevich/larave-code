<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function ($table) {
            $table->dropColumn('size_id');
            $table->dropColumn('color_id');
            $table->dropForeign('orders_product_id_foreign');
            $table->dropColumn('product_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function ($table) {
            $table->integer('product_id')->unsigned();
            $table->integer('size_id');
            $table->integer('color_id');
            $table->foreign('product_id')->references('id')->on('products');


        });
    }
}
