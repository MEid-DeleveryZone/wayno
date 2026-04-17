<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount',12,2)->unsigned()->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->bigInteger('promo_type_id')->unsigned()->nullable();
            $table->tinyInteger('allow_free_delivery')->default(0)->comment('0- No, 1- yes')->nullable();
            $table->integer('minimum_spend')->unsigned()->nullable();
            $table->integer('maximum_spend')->unsigned()->nullable();
            $table->tinyInteger('first_order_only')->default(0)->comment('0- No, 1- yes')->nullable();
            $table->tinyInteger('limit_per_user')->nullable();
            $table->tinyInteger('limit_total')->nullable();
            $table->tinyInteger('paid_by_vendor_admin')->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment('0- No, 1- yes')->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('promo_type_id')->references('id')->on('promo_types')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index('allow_free_delivery');
            $table->index('minimum_spend');
            $table->index('maximum_spend');
            $table->index('first_order_only');
            $table->index('limit_per_user');
            $table->index('limit_total');
            $table->index('paid_by_vendor_admin');
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocodes');
    }
}
