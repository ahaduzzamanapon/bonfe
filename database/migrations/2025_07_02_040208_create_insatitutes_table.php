<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInsatitutesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insatitutes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('insatitute_name');
            $table->integer('district');
            $table->string('address');
            $table->string('status');
            $table->text('description');
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
        Schema::drop('insatitutes');
    }
}
