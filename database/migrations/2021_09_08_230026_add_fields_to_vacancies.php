<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToVacancies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vacancies', function (Blueprint $table) {
            //
            $table->unsignedInteger('company')->nullable();
            $table->unsignedInteger('type')->nullable();
            $table->date('validity')->nullable();
            $table->text('experience')->nullable();
            $table->text('offer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancies', function (Blueprint $table) {
            //
            $table->dropColumn('company');
            $table->dropColumn('type');
            $table->dropColumn('validity');
            $table->dropColumn('experience');
            $table->dropColumn('offer');
        });
    }
}
