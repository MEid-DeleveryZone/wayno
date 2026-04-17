<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionInvoiceFeaturesVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_invoice_features_vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('subscription_invoice_id');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('feature_id')->nullable();
            $table->string('feature_title')->nullable();
            $table->timestamps();

            $table->index('subscription_invoice_id', 'vendor_subscription_invoice_id_index');
            $table->index('feature_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_invoice_features_vendor');
    }
}
