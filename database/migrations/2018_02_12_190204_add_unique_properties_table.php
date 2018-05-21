<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniquePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sizes', function ($table) {
            $table->unique('size');
        });
        Schema::table('colors', function ($table) {
            $table->unique('color');
        });
        Schema::table('way_pay', function ($table) {
            $table->unique('payName');
        });
        Schema::table('way_delivery', function ($table) {
            $table->unique('deliveryName');
        });
        Schema::table('categories', function ($table) {
            $table->unique('category');
        });
        Schema::table('images', function ($table) {
            $table->unique('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
