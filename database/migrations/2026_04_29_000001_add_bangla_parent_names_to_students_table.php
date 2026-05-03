<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBanglaParentNamesToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('father_name_bn')->nullable()->after('father_name');
            $table->string('mother_name_bn')->nullable()->after('mother_name');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['father_name_bn', 'mother_name_bn']);
        });
    }
}
