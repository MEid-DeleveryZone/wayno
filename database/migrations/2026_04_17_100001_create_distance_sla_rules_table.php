<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistanceSlaRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distance_sla_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('scope')->default('global');
            $table->decimal('min_distance', 8, 2);
            $table->decimal('max_distance', 8, 2);
            $table->unsignedInteger('time_with_rider');
            $table->unsignedInteger('time_without_rider');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->index(['vendor_id', 'min_distance', 'max_distance'], 'distance_sla_rules_vendor_distance_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distance_sla_rules');
    }
}
