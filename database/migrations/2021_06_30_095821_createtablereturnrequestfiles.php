<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createtablereturnrequestfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_return_request_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_return_request_id')->unsigned();
            $table->string('file', 500)->nullable();
            $table->foreign('order_return_request_id')->references('id')->on('order_return_requests')->onDelete('cascade');
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
        Schema::dropIfExists('order_return_request_files');
    }
}
