<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('desc')->nullable();
            $table->string('logo', 150)->nullable();
            $table->string('banner', 150)->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 15, 12)->nullable();
            $table->decimal('longitude', 16, 12)->nullable();
            $table->decimal('order_min_amount', 10, 2)->default(0);
            $table->string('order_pre_time', 40)->nullable();
            $table->string('auto_reject_time', 40)->nullable();
            $table->smallInteger('commission_percent')->default(1)->nullable();
            $table->decimal('commission_fixed_per_order', 10, 2)->default(0)->nullable();
            $table->decimal('commission_monthly', 10, 2)->default('0')->nullable();
            $table->tinyInteger('dine_in')->default('0')->comment('1 for yes, 0 for no');
            $table->tinyInteger('takeaway')->default('0')->comment('1 for yes, 0 for no');
            $table->tinyInteger('delivery')->default('0')->comment('1 for yes, 0 for no');
            $table->tinyInteger('status')->default('1')->comment('1-active, 0-pending, 2-blocked');
            $table->tinyInteger('add_category')->default(1)->comment('0 for no, 1 for yes');
            $table->tinyInteger('setting')->default(0)->comment('0 for no, 1 for yes');
            $table->timestamps();

            $table->index('name');
            $table->index('order_min_amount');
            $table->index('order_pre_time');
            $table->index('auto_reject_time');
            $table->index('commission_percent');
            $table->index('commission_fixed_per_order');
            $table->index('commission_monthly');
            $table->index('dine_in');
            $table->index('takeaway');
            $table->index('delivery');
            $table->index('add_category');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
