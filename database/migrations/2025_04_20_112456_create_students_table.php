<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('occupation_id');
            $table->string('registration_number');
            $table->string('candidate_id');
            $table->string('candidate_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('nid');
            $table->string('district_id');
            $table->string('upajila_id');
            $table->string('address');
            $table->date('date_of_birth');
            $table->integer('mobile_number');
            $table->string('email');
            $table->date('assessment_date');
            $table->string('assessment_venue');
            $table->string('assessment_center');
            $table->string('assessment_center_registration_number');
            $table->string('status');
            $table->string('exam_status');
            $table->string('chairmen_id');
            $table->string('chairmen_status');
            $table->string('districts_admin_id');
            $table->string('districts_admin_status');
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
        Schema::drop('students');
    }
}
