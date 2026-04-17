<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned();
            $table->string('action', 20)->default('add')->comment('add, update, delete, block, active');
            $table->string('updater_role')->default('SuperAdmin')->comment('SuperAdmin, Admin, Seller');
            $table->bigInteger('update_id')->unsigned()->nullable();
            $table->bigInteger('client_code')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_histories');
    }
}
