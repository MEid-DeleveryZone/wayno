<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_options', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50)->nullable();
            $table->bigInteger('addon_id')->unsigned();
            $table->smallInteger('position')->default(1);
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('addon_id')->references('id')->on('addon_sets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addon_options');
    }
}
