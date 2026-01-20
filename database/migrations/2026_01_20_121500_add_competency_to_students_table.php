<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompetencyColumnsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'competency_status')) {
                $table->string('competency_status')->nullable()->after('student_type'); // 'Competent', 'Not Yet Competent'
            }
            if (!Schema::hasColumn('students', 'competency_remarks')) {
                $table->string('competency_remarks')->nullable()->after('competency_status'); // '11,12,13' etc.
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'competency_status')) {
                $table->dropColumn('competency_status');
            }
            if (Schema::hasColumn('students', 'competency_remarks')) {
                $table->dropColumn('competency_remarks');
            }
        });
    }
}
