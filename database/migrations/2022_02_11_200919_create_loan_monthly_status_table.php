<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanMonthlyStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_monthly_status', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->integer('customer_id');
            $table->integer('monthly_pending_loan');
            $table->integer('amount_of_loan_paid_off')->default(0);
            $table->integer('pending_loan');
            $table->integer('interest')->default(0);
            $table->date('month_start_date');
            $table->date('month_end_date');
            $table->date('next_month_start_date');
            $table->integer('is_interest_calculated')->default(0);
            $table->date('interest_calculated_date');
            $table->integer('is_expire')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_monthly_status');
    }
}
