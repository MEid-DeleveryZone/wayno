<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclaturesTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('nomenclatures', function (Blueprint $table) {
            $table->dropColumn('value');
        });
        Schema::create('nomenclatures_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('language_id')->unsigned();
            $table->bigInteger('nomenclature_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nomenclatures_translations');
    }
}
