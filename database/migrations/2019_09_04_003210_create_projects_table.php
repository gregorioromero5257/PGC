<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('code')->nullable();
            $table->longText('name_es')->nullable();
            $table->longText('name_en')->nullable();
            $table->longText('contract_name')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->date('validity_of_confidentiality')->nullable();
            $table->longText('comments')->nullable();
            $table->unsignedInteger('contract_id')->nullable();
            $table->string('contract_creator')->nullable();
            $table->string('contractual_object')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('amount_mxn', 10, 2)->nullable();
            $table->decimal('amount_usd', 10, 2)->nullable();
            $table->date('contract_start')->nullable();
            $table->date('contract_end')->nullable();
            $table->string('duration')->nullable();
            $table->longText('description_es')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('short_description_es')->nullable();
            $table->longText('short_description_en')->nullable();
            $table->integer('interviews')->nullable();
            $table->integer('databases')->nullable();
            $table->integer('trainings')->nullable();
            $table->integer('study_cases')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
