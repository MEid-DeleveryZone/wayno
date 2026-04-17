<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDineInFieldsToClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('dinein_check')->nullable()->default(1)->comment('0-No, 1-Yes');
            $table->tinyInteger('takeaway_check')->nullable()->default(1)->comment('0-No, 1-Yes');
            $table->tinyInteger('delivery_check')->nullable()->default(1)->comment('0-No, 1-Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn('dinein_check');
            $table->dropColumn('takeaway_check');
            $table->dropColumn('delivery_check');
        });
    }
}
