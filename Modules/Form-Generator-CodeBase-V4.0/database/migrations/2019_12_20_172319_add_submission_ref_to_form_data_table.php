<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmissionRefToFormDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_data', function (Blueprint $table) {
            $table->string('submission_ref')
                ->after('form_id')
                ->nullable();

            $table->enum('status', ['complete', 'incomplete'])
                ->default('complete')
                ->after('source');
                
            $table->string('token')
                ->after('status')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('form_data', function (Blueprint $table) {
        //
        // });
    }
}
