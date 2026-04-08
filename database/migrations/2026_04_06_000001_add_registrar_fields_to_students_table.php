<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistrarFieldsToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('assistant_registrar_id')->nullable()->after('districts_admin_status');
            $table->string('assistant_registrar_status')->nullable()->after('assistant_registrar_id');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['assistant_registrar_id', 'assistant_registrar_status']);
        });
    }
}
