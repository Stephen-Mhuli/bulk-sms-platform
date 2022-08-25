<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WebhookInCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_numbers', function (Blueprint $table) {
            $table->string('webhook_url')->nullable();
            $table->string('webhook_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_numbers', function (Blueprint $table) {
            $table->dropColumn('webhook_url');
            $table->dropColumn('webhook_method');
        });
    }
}
