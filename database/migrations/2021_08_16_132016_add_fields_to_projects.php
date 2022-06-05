<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->date('date_contract')->nullable();
            $table->decimal('type_change', 6, 2)->nullable();
            $table->decimal('amount_before_iva', 10, 2)->nullable();
            $table->decimal('iva', 10, 2)->nullable();
            $table->decimal('currency_iva', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->dropColumn('date_contract');
            $table->dropColumn('type_change');
            $table->dropColumn('amount_before_iva');
            $table->dropColumn('iva');
            $table->dropColumn('currency_iva');
        });
    }
}
