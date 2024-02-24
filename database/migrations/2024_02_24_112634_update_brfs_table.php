<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBrfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('brfs', function (Blueprint $table) {
            $table->timestamp('lac_status_date')->nullable();
            $table->timestamp('librarian_status_date')->nullable();
            $table->timestamp('download_status_date')->nullable();
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
        Schema::table('brfs', function (Blueprint $table) {
            $table->dropColumn('download_status_date');
            $table->dropColumn('librarian_status_date');
            $table->dropColumn('lac_status_date');
        });
    }
}
