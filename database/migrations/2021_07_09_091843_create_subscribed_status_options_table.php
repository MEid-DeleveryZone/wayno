<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribedStatusOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribed_status_options', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedTinyInteger('type')->nullable()->comment('1-User, 2-Vendor');
            $table->unsignedTinyInteger('status')->default(1)->comment('0-Inactive, 1-Active');
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
        Schema::dropIfExists('subscribed_status_options');
    }
}
