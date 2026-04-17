<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->tinyInteger('position')->default(1);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->string('barcode', 20)->unique();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->bigInteger('tax_category_id')->unsigned()->nullable();

            $table->string('inventory_policy')->nullable();
            $table->string('fulfillment_service')->nullable();
            $table->string('inventory_management')->nullable();
            
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('tax_category_id')->references('id')->on('tax_categories')->onDelete('set null');
            $table->index('sku');
            $table->index('quantity');
            $table->index('price');
            $table->index('compare_at_price');
            $table->index('cost_price');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
