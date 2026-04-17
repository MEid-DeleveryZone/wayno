<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleImageUploadToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->tinyInteger('is_schedule_enabled')->after('is_dropoff_enabled')->default(1)->comment('1-active, 0-inactive');
            $table->tinyInteger('is_image_upload_enabled')->after('is_schedule_enabled')->default(1)->comment('1-active, 0-inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_schedule_enabled');
            $table->dropColumn('is_image_upload_enabled');
        });
    }
}
