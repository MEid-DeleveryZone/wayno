<?php

use App\Models\Language;
use App\Models\OrderRejectingReason;
use App\Models\OrderRejectingReasonTranslation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('order_rejecting_reason_translations')) {
            return;
        }

        $defaultLanguageId = Language::where('sort_code', 'en')->value('id') ?? 1;

        OrderRejectingReason::chunk(100, function ($reasons) use ($defaultLanguageId) {
            foreach ($reasons as $reason) {
                OrderRejectingReasonTranslation::updateOrCreate(
                    [
                        'order_rejecting_reason_id' => $reason->id,
                        'language_id' => $defaultLanguageId,
                    ],
                    [
                        'reason' => $reason->name,
                    ]
                );
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('order_rejecting_reason_translations')) {
            return;
        }

        $defaultLanguageId = Language::where('sort_code', 'en')->value('id') ?? 1;

        OrderRejectingReasonTranslation::where('language_id', $defaultLanguageId)->delete();
    }
};

