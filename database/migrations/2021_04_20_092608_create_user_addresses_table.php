<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('address')->nullable();
            $table->string('street')->nullable();
            $table->string('city', 60)->nullable();
            $table->string('state', 60)->nullable();
            $table->decimal('latitude', 15, 12)->nullable();
            $table->decimal('longitude', 16, 12)->nullable();
            $table->string('pincode', 60)->nullable();
            $table->tinyInteger('is_primary')->default('0')->comment('1 for yes, 0 for no');
            $table->tinyInteger('type')->default('1')->comment('1 - home', '2 - office', '3 - other');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
