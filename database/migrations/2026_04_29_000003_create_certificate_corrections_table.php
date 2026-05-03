<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateCorrectionsTable extends Migration
{
    public function up()
    {
        Schema::create('certificate_corrections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->date('application_date')->nullable();
            $table->json('correction_fields')->nullable();
            $table->text('reason')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->enum('status', ['pending', 'controller_approved', 'chairman_approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('controller_id')->nullable();
            $table->text('controller_remarks')->nullable();
            $table->datetime('controller_approved_at')->nullable();
            $table->unsignedBigInteger('chairman_id')->nullable();
            $table->text('chairman_remarks')->nullable();
            $table->datetime('chairman_approved_at')->nullable();
            $table->string('corrected_certificate_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_corrections');
    }
}
