<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_id')->comment('Who purchased the number');
            $table->string('number');
            $table->string('from');
            $table->text('obj')->comment('this is the response after purchase')->nullable();
            $table->double('purch_price')->default('0')->comment('Purchased price from number provider');
            $table->double('sell_price')->default('0')->comment('Selling price to the customer');
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('numbers');
    }
}
