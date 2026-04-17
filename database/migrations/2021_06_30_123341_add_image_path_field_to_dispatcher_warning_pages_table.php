<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagePathFieldToDispatcherWarningPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispatcher_warning_pages', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispatcher_warning_pages', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
}
