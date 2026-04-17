<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlanFeaturesUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plan_features_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscription_plan_id')->unsigned();
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans_user');
            $table->bigInteger('feature_id')->unsigned();
            $table->foreign('feature_id')->references('id')->on('subscription_features_list_user');
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
        Schema::dropIfExists('subscription_plan_features_user');
    }
}
