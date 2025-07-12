<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statement', function (Blueprint $table) {
            $table->id();
            $table->integer('month_id');
            $table->integer('customer_id');
            $table->integer('amount');
            $table->text('details')->nullable();
            $table->date('trans_date');
            $table->time('trans_time');
            $table->text('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statement');
    }
}
