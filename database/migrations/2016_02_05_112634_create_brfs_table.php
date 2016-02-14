<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('brfs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doctype');
            $table->string('author');
            $table->string('title');
            $table->string('publisher');
            $table->string('agency')->nullable();
            $table->string('isbn')->nullable();
            $table->string('volumne')->nullable();
            $table->string('price')->nullable();
            $table->string('sectioncatalogue')->nullable();
            $table->string('numberofcopies')->nullable();
            $table->string('laravel_user_id')->nullable();
            $table->string('iitm_dept_code')->nullable();
            $table->string('lac_status')->nullable();
            $table->string('librarian_status')->nullable();
            $table->string('download_status')->nullable();
            $table->string('remarks')->nullable();
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
        //
        Schema::drop('brfs');
    }
}
