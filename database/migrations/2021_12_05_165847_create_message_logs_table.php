<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('queue_id')->nullable();
            $table->unsignedInteger('campaign_id')->nullable();
            $table->unsignedInteger('message_id');
            $table->text('body');
            $table->string('from')->comment('devices table id');
            $table->string('device_unique_id');
            $table->string('to');
            $table->enum('type',['inbox','sent']);
            $table->enum('status',['pending','succeed','failed'])->default('pending');
            $table->string('message_obj')->nullable()->comment('response after sending sms using API');
            $table->text('message_files')->nullable()->comment('MMS files');
            $table->string('response_code')->nullable();
            $table->string('response_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_logs');
    }
}
