<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insatitutes', function (Blueprint $table) {
            $table->string('center_reg_num')->nullable()->after('code')->comment('Center Registration number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insatitutes', function (Blueprint $table) {
            $table->dropColumn('center_reg_num');
        });
    }
};
