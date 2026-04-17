<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->mediumText('title');
            $table->mediumText('slug');
            $table->longText('description');
            $table->mediumText('meta_title')->nullable();
            $table->mediumText('meta_keyword')->nullable();;
            $table->mediumText('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('pages');
    }
}
