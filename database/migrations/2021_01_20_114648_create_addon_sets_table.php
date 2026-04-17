<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_sets', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->tinyInteger('min_select')->default(1);
            $table->tinyInteger('max_select')->default(1);
            $table->smallInteger('position')->default(1);
            $table->tinyInteger('status')->default('1')->comment('0 - pending, 1 - active, 2 - blocked');
            $table->tinyInteger('is_core')->default('1')->comment('0 - no, 1 - yes');
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addon_sets');
    }
}
