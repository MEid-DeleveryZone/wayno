<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_currencies', function (Blueprint $table) {
            $table->string('client_code', 10)->nullable();
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->tinyInteger('is_primary')->default(0)->comment('1 for yes, 0 for no');
            $table->decimal('doller_compare')->nullable();
            $table->timestamps();
        });

        Schema::table('client_currencies', function (Blueprint $table) {
            $table->foreign('client_code')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_currencies');
    }
}
