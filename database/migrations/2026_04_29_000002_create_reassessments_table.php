<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReassessmentsTable extends Migration
{
    public function up()
    {
        Schema::create('reassessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->tinyInteger('attempt_number')->default(1);
            $table->date('application_date')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', [
                'pending', 'scheduled', 'result_entered',
                'waiting_controller', 'waiting_chairman', 'approved', 'rejected'
            ])->default('pending');
            $table->date('scheduled_date')->nullable();
            $table->unsignedBigInteger('scheduled_center_id')->nullable();
            $table->enum('exam_status', ['Pending', 'Passed', 'Fail', 'Absent'])->default('Pending');
            $table->string('exam_result_sheet')->nullable();
            $table->unsignedBigInteger('controller_id')->nullable();
            $table->datetime('controller_approved_at')->nullable();
            $table->unsignedBigInteger('chairman_id')->nullable();
            $table->datetime('chairman_approved_at')->nullable();
            $table->boolean('certificate_generated')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reassessments');
    }
}
