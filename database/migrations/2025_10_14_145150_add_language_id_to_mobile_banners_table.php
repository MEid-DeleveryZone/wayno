<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageIdToMobileBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobile_banners', function (Blueprint $table) {
            $table->bigInteger('language_id')->unsigned()->nullable()->after('name');
            $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade')->onDelete('set null');
            $table->index('language_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mobile_banners', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->dropIndex(['language_id']);
            $table->dropColumn('language_id');
        });
    }
}
