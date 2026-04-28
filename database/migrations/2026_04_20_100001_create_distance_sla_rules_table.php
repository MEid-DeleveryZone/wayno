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
            $table->id();
            $table->unsignedBigInteger('distance_sla_group_id');
            $table->decimal('distance_from', 8, 2);
            $table->decimal('distance_to', 8, 2)->nullable();
            $table->unsignedInteger('time_with_rider');
            $table->unsignedInteger('time_without_rider');
            $table->timestamps();

            $table->foreign('distance_sla_group_id')
                ->references('id')
                ->on('distance_sla_groups')
                ->onDelete('cascade');

            $table->unique(['distance_sla_group_id', 'distance_from', 'distance_to'], 'distance_sla_rules_group_from_to_unique');
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
