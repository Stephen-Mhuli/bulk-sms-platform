<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        id, customer_id, title, from, start_date, end_date, message_body, to,message_send_rate;
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('customer_id');
            $table->text('device_ids');
            $table->text('from_devices');
            $table->longText('to_number');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('start_time');
            $table->string('end_time');
            $table->integer('template_id')->nullable();
            $table->longText('message_body');
            $table->enum('status',['importing','running','paused','completed','failed'])->default('importing');
            $table->string('import_fail_message')->nullable();
            $table->integer('message_send_rate');
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
        Schema::dropIfExists('campaigns');
    }
}
