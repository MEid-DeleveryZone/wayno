<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('subscribed_status_options');
        Schema::dropIfExists('subscribed_users');
        Schema::dropIfExists('user_subscription_features');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('vendor_subscription_features');
        Schema::dropIfExists('vendor_subscriptions');
        Schema::dropIfExists('subscription_features_list');
        Schema::dropIfExists('subscription_validities');
        Schema::dropIfExists('plan_subscription_usage');
        Schema::dropIfExists('plan_subscriptions');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('plans');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
