<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 50)->unique();
            $table->string('title', 60)->nullable();
            $table->string('url_slug', 100)->nullable();
            $table->longText('description')->nullable();
            $table->longText('body_html')->nullable();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->bigInteger('country_origin_id')->unsigned()->nullable();
            $table->tinyInteger('is_new')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('is_featured')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('is_live')->default(0)->comment('0 - draft, 1 - published, 2 - blocked');
            $table->tinyInteger('is_physical')->default(0)->comment('0 - no, 1 - yes');
            $table->decimal('weight', 10, 4)->nullable();
            $table->string('weight_unit', 10)->nullable();
            $table->tinyInteger('has_inventory')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('has_variant')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('sell_when_out_of_stock')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('requires_shipping')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('Requires_last_mile')->default(0)->comment('0 - no, 1 - yes');
            $table->decimal('averageRating', 4, 2)->nullable();
            $table->dateTime('publish_at')->nullable();

            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('set null');
            $table->foreign('country_origin_id')->references('id')->on('countries')->onDelete('set null');
            $table->index('is_new');
            $table->index('is_featured');
            $table->index('is_live');
            $table->index('is_physical');
            $table->index('has_inventory');
            $table->index('sell_when_out_of_stock');
            $table->index('requires_shipping');
            $table->index('Requires_last_mile');
            $table->index('averageRating');
        });
    }

    


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
