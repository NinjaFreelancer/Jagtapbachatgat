<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_deposit', function (Blueprint $table) {
            $table->id();
            $table->longText('customer_name');
            $table->bigInteger('mobile_no');
            $table->integer('FD_amount');
            $table->date('date_of_deposit');
            $table->date('trans_date');
            $table->time('trans_time');
            $table->integer('interest_rate')->default(0);
            $table->integer('completed_months')->default(0);
            $table->integer('extra_days')->default(0);
            $table->integer('is_interest_calculated')->default(0);
            $table->date('interest_calculated_up_to');
            $table->integer('interest')->default(0);
            $table->integer('is_fd_amount_disbursed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_deposit');
    }
}
