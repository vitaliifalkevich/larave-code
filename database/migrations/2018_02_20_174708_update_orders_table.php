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
            $table->dropColumn('firstName');
            $table->dropColumn('lastName');
            $table->dropColumn('phone');
            $table->dropColumn('adress');
            $table->integer('size_id');
            $table->integer('color_id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');

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
            $table->string('firstName',100);
            $table->string('lastName',100);
            $table->integer('phone');
            $table->string('adress',100);
            $table->dropColumn('size_id');
            $table->dropColumn('color_id');
            $table->dropForeign('client_id');
            $table->dropColumn('client_id');

        });
    }
}
