<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_queues', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('message_id');
            $table->unsignedInteger('campaign_id')->nullable();
            $table->string('device_unique_id');
            $table->text('body');
            $table->string('from')->comment('devices table id');
            $table->string('to');
            $table->dateTime('schedule_datetime')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->enum('schedule_completed',['yes','no'])->default('no');
            $table->text('message_files')->nullable()->comment('MMS files in json encoded');
            $table->enum('status',['running','fetched','paused','failed','delivered'])->default('running');
            $table->string('response_code')->nullable();
            $table->string('response_id')->nullable();
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
        Schema::dropIfExists('sms_queues');
    }
}
