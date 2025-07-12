<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFdAmountDisbursedTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fd_amount_disbursed_transaction', function (Blueprint $table) {
            $table->id();
            $table->integer('fd_id');
            $table->integer('amount');
            $table->date('disbursed_date');
            $table->date('trans_date');
            $table->time('trans_time');
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
        Schema::dropIfExists('fd_amount_disbursed_transaction');
    }
}
