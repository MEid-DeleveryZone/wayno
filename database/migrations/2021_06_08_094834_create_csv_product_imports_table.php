<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvProductImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_product_imports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->bigInteger('uploaded_by')->unsigned()->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            $table->tinyInteger('status')->nullable()->comment('1-Pending, 2-Success, 3-Failed, 4-In-progress');
            $table->longText('error')->nullable();
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
        Schema::dropIfExists('csv_product_imports');
    }
}
