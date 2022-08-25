<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('campaign_id')->nullable();
            $table->text('body');
            $table->longText('numbers')->comment('array of contact numbers');
            $table->dateTime('schedule_datetime')->nullable();
            $table->enum('type',['inbox','sent'])->nullable();
            $table->enum('read',['yes','no'])->default('no');
            $table->enum('schedule_completed',['yes','no'])->default('no');
            $table->string('message_obj')->nullable()->comment('response after sending sms using API');
            $table->text('message_files')->nullable()->comment('MMS files');
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
        Schema::dropIfExists('messages');
    }
}
