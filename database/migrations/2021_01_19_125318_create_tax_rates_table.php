<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 100)->nullable();
            $table->tinyInteger('is_zip')->default(1)->comment('0 - no, 1 - yes');
            $table->string('zip_code', 10)->nullable();
            $table->string('zip_from', 10)->nullable();
            $table->string('zip_to', 100)->nullable();
            $table->string('state', 40)->nullable();
            $table->string('country', 40)->nullable();
            $table->decimal('tax_rate',  10, 2)->nullable();
            $table->decimal('tax_amount',  10, 2)->nullable();
            $table->timestamps();

            $table->index('is_zip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_rates');
    }
}
