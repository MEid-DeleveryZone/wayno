<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCelebritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('celebrities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 60)->unique()->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone_number', 24)->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 - pending, 1 - active, 2 - inactive, 3 - deleted');
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
        Schema::dropIfExists('celebrities');
    }
}
