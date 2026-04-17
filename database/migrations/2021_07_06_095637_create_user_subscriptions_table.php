<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->mediumText('slug')->nullable();
            $table->string('image')->nullable();
            $table->longText('Description')->nullable();
            $table->decimal('price', 12, 2)->unsigned()->default(0);
            $table->bigInteger('validity_id')->unsigned();
            $table->foreign('validity_id')->references('id')->on('subscription_validities')->onDelete('cascade');
            $table->enum('status',[0, 1])->default(1)->comment('0=Inactive, 1=Active');
            $table->tinyInteger('is_deleted')->unsigned()->default(0);
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
        Schema::dropIfExists('user_subscriptions');
    }
}
