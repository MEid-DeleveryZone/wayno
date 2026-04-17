<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode_restrictions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promocode_id')->unsigned()->nullable();
            $table->tinyInteger('restriction_type')->default(0)->comment('0- product, 1-vendor, 2-category')->nullable();
            $table->bigInteger('data_id')->unsigned()->nullable();
            $table->tinyInteger('is_included')->default(1)->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_excluded')->default(1)->comment('1 for yes, 0 for no');
   
            $table->timestamps();
            $table->index('is_included');
            $table->index('is_excluded');
            $table->foreign('promocode_id')->references('id')->on('promocodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocode_restrictions');
    }
}
