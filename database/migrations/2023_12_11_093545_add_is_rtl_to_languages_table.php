<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class AddIsRtlToLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->tinyInteger('is_rtl')->default(0)->comment('1 for right to left, 0 for left to right');    
        });
        
        DB::table('languages')
        ->whereIn('name', ['Arabic', 'Urdu','Persian','Sindhi','Yiddish'])
        ->update(['is_rtl' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('is_rtl');
        });
    }
}
