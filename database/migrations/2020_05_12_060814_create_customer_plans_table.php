<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('plan_id');
            $table->double('price')->default(0);
            $table->unsignedInteger('contact_limit')->default(0);
            $table->unsignedInteger('daily_send_limit')->default(0);
            $table->unsignedInteger('daily_receive_limit')->default(0);
            $table->unsignedInteger('device_limit')->default(0);
            $table->enum('is_current',['yes','no'])->default('no');
            $table->enum('payment_status',['paid','unpaid'])->default('unpaid');
            $table->enum('status',['pending','accepted','rejected','hold'])->default('pending');
            $table->unsignedInteger('sms_limit')->default(0);
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
        Schema::dropIfExists('customer_plans');
    }
}
