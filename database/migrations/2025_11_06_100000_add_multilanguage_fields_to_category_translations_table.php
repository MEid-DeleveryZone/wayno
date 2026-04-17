<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultilanguageFieldsToCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->string('order_detail_placeholder')->after('name')->nullable();
            $table->string('vendor_heading')->after('order_detail_placeholder')->nullable();
            $table->string('product_heading')->after('vendor_heading')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn('order_detail_placeholder');
            $table->dropColumn('vendor_heading');
            $table->dropColumn('product_heading');
        });
    }
}

