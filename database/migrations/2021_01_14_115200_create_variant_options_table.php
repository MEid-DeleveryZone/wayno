<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_options', function (Blueprint $table) {

            $table->id();
            $table->string('title', 150)->nullable();
            $table->bigInteger('variant_id')->unsigned()->nullable();
            $table->string('hexacode', 10)->nullable();
            $table->smallInteger('position')->default(1);
            $table->timestamps();

            $table->index('position');
            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variant_options');
    }
}

