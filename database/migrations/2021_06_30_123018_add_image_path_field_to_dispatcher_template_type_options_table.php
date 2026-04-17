<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagePathFieldToDispatcherTemplateTypeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispatcher_template_type_options', function (Blueprint $table) {
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
        Schema::table('dispatcher_template_type_options', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
}
