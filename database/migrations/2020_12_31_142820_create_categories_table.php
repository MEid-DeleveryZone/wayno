<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 150)->nullable();
            $table->string('slug', 30)->unique();
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->string('image', 150)->nullable();
            $table->tinyInteger('is_visible')->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 - pending, 1 - active, 2 - blocked');
            $table->smallInteger('position')->default('1')->comment('for same position, display asc order');
            $table->tinyInteger('is_core')->default('1')->comment('0 - no, 1 - yes');
            $table->tinyInteger('can_add_products')->default('0')->comment('0 - no, 1 - yes');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->string('client_code', 10)->nullable();
            $table->string('display_mode')->nullable()->comment('only products name, product with description');
            $table->timestamps();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('client_code')->references('code')->on('clients')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('set null');
            $table->index('status');
            $table->index('is_core');
            $table->index('position');
            $table->index('can_add_products');
            $table->index('display_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}