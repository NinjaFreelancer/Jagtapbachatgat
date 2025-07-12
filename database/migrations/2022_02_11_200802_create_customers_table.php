<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('acc_no');
            $table->longText('full_name');
            $table->longText('shop_name');
            $table->integer('balance')->default(0);
            $table->integer('per_month_bachat');
            $table->bigInteger('mobile_no');
            $table->string('email')->nullable();
            $table->bigInteger('aadhaar')->nullable();
            $table->string('pan')->nullable();
            $table->longText('address');
            $table->longText('profile');
            $table->date('account_opening_date');
            $table->date('account_expiry_date');
            $table->longText('pass');
            $table->integer('is_active')->default(1);
            $table->integer('status')->default(0);
            $table->integer('is_all_months_status_ready')->default(0);
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
        Schema::dropIfExists('customers');
    }
}
