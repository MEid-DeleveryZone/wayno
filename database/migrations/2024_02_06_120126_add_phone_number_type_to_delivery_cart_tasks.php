<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneNumberTypeToDeliveryCartTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_cart_tasks', function (Blueprint $table) {
            $table->string('phone_number_type')->after('name')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_cart_tasks', function (Blueprint $table) {
            $table->dropColumn('phone_number_type');
        });
    }
}
