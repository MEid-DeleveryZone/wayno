<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTableOrderNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'item_count')) {
                $table->dropColumn('item_count');
            }
            if (Schema::hasColumn('orders', 'tax_rate_id')) {
                $table->dropColumn('tax_rate_id');
            }
            if (Schema::hasColumn('orders', 'vendor_count')) {
                $table->dropColumn('vendor_count');
            }
            // if (Schema::hasColumn('orders', 'promocode_id')) {
            //     $table->dropColumn('promocode_id');
            // }
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('orders', 'recipient_name')) {
                $table->dropColumn('recipient_name');
            }if (Schema::hasColumn('orders', 'recipient_email')) {
                $table->dropColumn('recipient_email');
            }if (Schema::hasColumn('orders', 'recipient_number')) {
                $table->dropColumn('recipient_number');
            }
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('created_by')->after('id')->nullable();
            $table->string('order_number')->after('created_by')->nullable();
            $table->tinyInteger('payment_option_id')->after('order_number')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_number')->nullable();
        });
    }
}
