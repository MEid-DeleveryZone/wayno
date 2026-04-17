<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplePlaystoreLink extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
       Schema::table('client_preferences', function (Blueprint $table) {
           $table->mediumText('android_app_link')->nullable();
           $table->mediumText('ios_link')->nullable();
       });
   } 

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
       Schema::table('client_preferences', function (Blueprint $table) {
           $table->dropColumn('android_app_link');
           $table->dropColumn('ios_link');
       });
   }
}
