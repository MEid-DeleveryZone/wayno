<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_languages', function (Blueprint $table) {
            $table->string('client_code', 10)->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->tinyInteger('is_primary')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_active')->default(0)->comment('1 for yes, 0 for no');
            $table->timestamps();
        });

        Schema::table('client_languages', function (Blueprint $table) {
            $table->foreign('client_code')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_languages');
    }
}
