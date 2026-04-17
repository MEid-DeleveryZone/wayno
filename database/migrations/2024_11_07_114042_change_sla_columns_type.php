<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSlaColumnsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Change the column types to integer
            $table->integer('sla_same_emirates')->change();
            $table->integer('sla_diff_emirates')->change();

            // Add new enum columns for frequency
            $table->enum('same_emirate_frequency', ['days', 'hours'])->nullable()->after('sla_same_emirates');
            $table->enum('diff_emirate_frequency', ['days', 'hours'])->nullable()->after('sla_diff_emirates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert the integer columns back to string (since changing column types directly can be problematic)
            $table->string('sla_same_emirates')->change();
            $table->string('sla_diff_emirates')->change();

            // Drop the newly added enum columns if they exist
            if (Schema::hasColumn('products', 'same_emirate_frequency')) {
                $table->dropColumn('same_emirate_frequency');
            }
            if (Schema::hasColumn('products', 'diff_emirate_frequency')) {
                $table->dropColumn('diff_emirate_frequency');
            }
        });
    }
}
