<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('book_budgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iitm_dept_code');
            $table->string('year_from_until');
            $table->string('budget_allocated');
            $table->string('budget_spent');
            $table->string('budget_on_order');
            $table->string('budget_available');
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
        Schema::drop('book_budgets');
    }
}
