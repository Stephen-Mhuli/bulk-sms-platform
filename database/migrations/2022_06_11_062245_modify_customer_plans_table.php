<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCustomerPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_plans', function (Blueprint $table) {
            $table->timestamp('renew_date')->nullable();
            $table->enum('recurring_type',['onetime','weekly','monthly','yearly'])->default('onetime');
            $table->enum('expiry_notified',['yes','no'])->default('no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_plans', function (Blueprint $table) {
            $table->dropColumn('renew_date');
            $table->dropColumn('recurring_type');
            $table->dropColumn('expiry_notified');
        });
    }
}
