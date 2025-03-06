<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBrfsV2Table extends Migration
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
            $table->string('librarian_approver_iitm_id')->nullable();
            $table->string('downloader_approver_iitm_id')->nullable();
            $table->string('account_number_of_book')->nullable();
            $table->string('call_number_of_book')->nullable();
            $table->string('purchase_price_of_book')->nullable();
            $table->string('optional_ebook_hyperlink')->nullable();
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
            $table->dropColumn('optional_ebook_hyperlink');
            $table->dropColumn('purchase_price_of_book');
            $table->dropColumn('call_number_of_book');
            $table->dropColumn('account_number_of_book');
            $table->dropColumn('downloader_approver_iitm_id');
            $table->dropColumn('librarian_approver_iitm_id');
        });
    }
}
