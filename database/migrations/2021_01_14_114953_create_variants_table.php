<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->tinyInteger('type')->default(1)->comment('1 for dropdown, 2 for color');
            $table->smallInteger('position')->default(1);
            $table->tinyInteger('status')->default('1')->comment('0 - pending, 1 - active, 2 - blocked');
            $table->timestamps();

            $table->index('type');
            $table->index('position');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variants');
    }
}
