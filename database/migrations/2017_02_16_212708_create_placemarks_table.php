<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placemarks', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name')->default('');
            $t->string('description')->default('');
            $t->string('coords');
            $t->integer('type_id')->unsigned();

            $t->foreign('type_id')
                ->references('id')
                ->on('placemarks_type')
                ->onDelete('cascade');

            $t->softDeletes();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('placemarks');
    }
}
