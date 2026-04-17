<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardSettingstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onboard_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key_value')->nullable();
            $table->tinyInteger('enable_from')->comment('1 : For GodPanel','2:For on Admin')->default(1);
            $table->tinyInteger('on_off')->comment('0 : For off','1:For on')->default(0);
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
        Schema::dropIfExists('onboard_settings');
    }
}
