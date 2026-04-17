<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('dimensions')->after('returnable');
            $table->string('sla_same_emirates')->after('returnable');
            $table->string('sla_diff_emirates')->after('returnable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            Schema::dropColumn('dimensions');
            Schema::dropColumn('sla_same_emirates');
            Schema::dropColumn('sla_diff_emirates');
        });
    }
}
