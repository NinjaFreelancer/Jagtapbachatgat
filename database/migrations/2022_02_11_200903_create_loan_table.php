<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('loan_no');
            $table->integer('amount');
            $table->integer('pending_loan');
            $table->integer('monthly_emi');
            $table->integer('shares_amount');
            $table->integer('completed_months')->default(0);
            $table->integer('extra_days')->default(0);
            $table->integer('interest')->default(0);
            $table->date('interest_calculated_up_to');
            $table->integer('is_interest_calculated')->default(0);
            $table->date('loan_start_date');
            $table->date('loan_end_date');
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan');
    }
}
