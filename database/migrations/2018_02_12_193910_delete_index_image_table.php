<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteIndexImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('index_image', function ($table) {
            $table->dropForeign('index_image_image_id_foreign');
        });
        Schema::table('products', function ($table) {
            $table->dropForeign('products_index_image_id_foreign');
        });
        Schema::drop('index_image');
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
