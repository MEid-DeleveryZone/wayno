<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription_features', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscription_id')->unsigned();
            $table->foreign('subscription_id')->references('id')->on('user_subscriptions')->onDelete('cascade');
            $table->bigInteger('feature_id')->unsigned();
            $table->foreign('feature_id')->references('id')->on('subscription_features_list')->onDelete('cascade');
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
        Schema::dropIfExists('user_subscription_features');
    }
}
