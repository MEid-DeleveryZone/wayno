<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateExistingNotificationTemplateData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the columns exist before migrating
        if (Schema::hasColumn('notification_templates', 'subject') && Schema::hasColumn('notification_templates', 'content')) {
            // Get all existing notification templates
            $templates = DB::table('notification_templates')->get();
            
            foreach ($templates as $template) {
                // Create translation record for default language (English - ID: 1)
                DB::table('notification_template_translations')->insert([
                    'notification_template_id' => $template->id,
                    'language_id' => 1, // Default English language
                    'subject' => $template->subject,
                    'content' => $template->content,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Drop the subject and content columns from notification_templates
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->dropColumn(['subject', 'content']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add back the columns
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->mediumText('subject')->nullable();
            $table->longText('content')->nullable();
        });
        
        // Migrate data back from translations to main table (for first translation only)
        $templates = DB::table('notification_templates')->get();
        
        foreach ($templates as $template) {
            $translation = DB::table('notification_template_translations')
                ->where('notification_template_id', $template->id)
                ->where('language_id', 1)
                ->first();
            
            if ($translation) {
                DB::table('notification_templates')
                    ->where('id', $template->id)
                    ->update([
                        'subject' => $translation->subject,
                        'content' => $translation->content
                    ]);
            }
        }
    }
}
