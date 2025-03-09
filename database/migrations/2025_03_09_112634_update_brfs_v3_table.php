<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBrfsV3Table extends Migration
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
            $table->string('purchase_price_approver_iitm_id')->nullable();
            $table->string('purchase_target_year_from_until')->nullable();
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
            $table->dropColumn('purchase_target_year_from_until');
            $table->dropColumn('purchase_price_approver_iitm_id');
        });
    }
}
