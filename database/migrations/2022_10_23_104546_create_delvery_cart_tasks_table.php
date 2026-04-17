<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelveryCartTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_cart_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_cart_id');
            $table->tinyInteger('task_type_id');
            $table->integer('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('short_name')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
            $table->foreign('delivery_cart_id')->references('id')->on('delivery_carts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delvery_cart_tasks');
    }
}
