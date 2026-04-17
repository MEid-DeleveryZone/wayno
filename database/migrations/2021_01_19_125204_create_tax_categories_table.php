<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->string('code', 100)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('is_core')->default('1')->comment('0 - no, 1 - yes');
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onUpdate('cascade')->onDelete('set null');
            $table->index('code');
            $table->index('is_core');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_categories');
    }
}
