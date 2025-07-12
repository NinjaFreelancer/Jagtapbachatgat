<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionCollectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_collector', function (Blueprint $table) {
            $table->id();
            $table->string('acc_no');
            $table->longText('full_name');
            $table->bigInteger('mobile_no');
            $table->string('email');
            $table->longText('address');
            $table->longText('profile');
            $table->longText('pin');
            $table->longText('pass');
            $table->integer('status')->default(0);
            $table->integer('is_account_ready_to_reuse')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_collector');
    }
}