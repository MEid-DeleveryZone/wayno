<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('order_products', function (Blueprint $table) {
            if (Schema::hasColumn('order_products', 'is_tax_applied')) {
                $table->dropColumn('is_tax_applied');
            }
            if (Schema::hasColumn('order_products', 'tax_rate_id')) {
                $table->dropColumn('tax_rate_id');
            }
        });
        Schema::table('order_products', function (Blueprint $table) {
            $table->string('image')->after('product_name')->nullable();
            $table->string('price')->after('image')->nullable();
            $table->string('taxable_amount')->after('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            //
        });
    }
}
