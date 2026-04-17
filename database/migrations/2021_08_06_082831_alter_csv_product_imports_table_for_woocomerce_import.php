<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCsvProductImportsTableForWoocomerceImport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csv_product_imports', function (Blueprint $table) {
            $table->json('raw_data')->nullable()->after('status');
            $table->tinyInteger('type')->nullable()->comment('0 for csv, 1 for woocommerce')->after('uploaded_by')->default(0);
        });
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
