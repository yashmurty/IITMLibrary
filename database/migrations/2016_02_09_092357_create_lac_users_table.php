<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLacUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('lac_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('laravel_user_id');
            $table->string('iitm_id');
            $table->string('iitm_dept_code');
            $table->string('lac_email_id');
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
        Schema::drop('lac_users');
    }
}
