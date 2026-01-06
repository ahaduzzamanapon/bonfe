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
            $table->string('type')->nullable()->after('insatitute_name'); // e.g., IBC, OBC
            $table->string('code')->nullable()->after('type'); // e.g., 0001
        });

        Schema::table('occupations', function (Blueprint $table) {
            $table->string('code')->nullable()->after('title'); // e.g., CGD
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name_en'); // e.g., 18
        });
    }

    public function down()
    {
        Schema::table('insatitutes', function (Blueprint $table) {
            $table->dropColumn(['type', 'code']);
        });

        Schema::table('occupations', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
