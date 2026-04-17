<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppDynamicTutorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_dynamic_tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 255)->nullable();
            $table->integer('sort')->default(1)->nullable();
            $table->string('file_type', 50)->default('image')->comment('image/video')->nullable();
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
        Schema::dropIfExists('app_dynamic_tutorials');
    }
}
