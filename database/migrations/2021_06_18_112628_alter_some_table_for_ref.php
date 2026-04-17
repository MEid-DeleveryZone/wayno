<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSomeTableForRef extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_refferals', function (Blueprint $table) {
            $table->dropColumn('refferal_code');
            $table->dropColumn('reffered_by');
        });
        Schema::table('user_refferals', function (Blueprint $table) {
            $table->string('refferal_code', 15)->after('id')->nullable();
            $table->bigInteger('reffered_by')->after('refferal_code')->unsigned()->nullable();
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
