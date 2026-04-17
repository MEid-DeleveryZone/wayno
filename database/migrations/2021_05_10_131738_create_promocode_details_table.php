<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promocodes', function (Blueprint $table) {
            $table->tinyInteger('restriction_on')->default(0)->comment('0- product, 1-vendor')->nullable();
            $table->tinyInteger('restriction_type')->default(0)->comment('0- Include, 1-Exclude')->nullable();
        });
        Schema::create('promocode_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promocode_id')->unsigned()->nullable();
            $table->bigInteger('refrence_id')->unsigned()->nullable();
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
        Schema::dropIfExists('promocode_details');
    }
}
