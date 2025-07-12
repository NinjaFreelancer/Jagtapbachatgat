<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBachatMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bachat_monthly', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('monthly_bachat_amount');
            $table->integer('credited')->default(0);
            $table->integer('pending');
            $table->integer('is_received')->default(0);
            $table->date('month_start_date');
            $table->date('month_end_date');
            $table->date('next_month_start_date');
            $table->integer('is_expire')->default(0);
            $table->integer('penalty_amount')->default(0);
            $table->integer('is_penalty_apply')->default(0);
            $table->integer('did_the_fine_count')->default(0);
            $table->date('penalty_credited_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bachat_monthly');
    }
}