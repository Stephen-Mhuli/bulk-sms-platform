<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_id');
            $table->string('title');
            $table->double('price');
            $table->unsignedInteger('contact_limit');
            $table->unsignedInteger('daily_send_limit');
            $table->unsignedInteger('daily_receive_limit');
            $table->unsignedInteger('device_limit');
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('plans');
    }
}
