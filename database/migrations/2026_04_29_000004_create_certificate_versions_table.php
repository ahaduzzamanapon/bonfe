<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateVersionsTable extends Migration
{
    public function up()
    {
        Schema::create('certificate_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->unsignedBigInteger('correction_id')->nullable();
            $table->tinyInteger('version')->default(1);
            $table->json('snapshot_data')->nullable();
            $table->string('certificate_path')->nullable();
            $table->datetime('issued_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_versions');
    }
}
