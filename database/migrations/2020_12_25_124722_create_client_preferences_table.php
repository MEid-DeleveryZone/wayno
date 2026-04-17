<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('client_code', 10)->unique()->nullable();
            $table->string('theme_admin', 25)->default('light')->comment('Light, Dark');
            $table->string('distance_unit', 25)->default('metric')->comment('metric, imperial');
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->string('date_format', 25)->default('Y-m-d');
            $table->string('time_format', 25)->default('H:i');
            $table->tinyInteger('fb_login')->default(0)->comment('1 - enable, 0 - disable')->index();
            $table->string('fb_client_id', 100)->nullable();
            $table->string('fb_client_secret', 100)->nullable();
            $table->string('fb_client_url', 200)->nullable();
            $table->tinyInteger('twitter_login')->default(0)->comment('1 - enable, 0 - disable')->index();
            $table->string('twitter_client_id', 100)->nullable();
            $table->string('twitter_client_secret', 100)->nullable();
            $table->string('twitter_client_url', 200)->nullable();
            $table->tinyInteger('google_login')->default(0)->comment('1 - enable, 0 - disable')->index();
            $table->string('google_client_id', 100)->nullable();
            $table->string('google_client_secret', 100)->nullable();
            $table->string('google_client_url', 200)->nullable();
            $table->tinyInteger('apple_login')->default(0)->comment('1 - enable, 0 - disable')->index();
            $table->string('apple_client_id', 100)->nullable();
            $table->string('apple_client_secret', 100)->nullable();
            $table->string('apple_client_url', 200)->nullable();
            $table->string('Default_location_name', 200)->nullable();
            $table->decimal('Default_latitude', 15, 12)->default(0);
            $table->decimal('Default_longitude', 16, 12)->default(0);
            $table->bigInteger('map_provider')->unsigned()->nullable();
            $table->string('map_key', 100)->nullable();
            $table->string('map_secret', 100)->nullable();
            $table->bigInteger('sms_provider')->unsigned()->nullable();
            $table->string('sms_key', 100)->nullable();
            $table->string('sms_secret', 100)->nullable();
            $table->string('sms_from', 20)->nullable();
            $table->tinyInteger('verify_email')->default(0)->comment('0 - no, 1 - yes')->index();
            $table->tinyInteger('verify_phone')->default(0)->comment('0 - no, 1 - yes')->index();
            $table->bigInteger('web_template_id')->unsigned()->nullable();
            $table->bigInteger('app_template_id')->unsigned()->nullable();
            $table->string('personal_access_token_v1', 100)->nullable();
            $table->string('personal_access_token_v2', 100)->nullable();
            $table->string('mail_type', 20)->default('smtp')->nullable();
            $table->string('mail_driver', 20)->nullable();
            $table->string('mail_host', 30)->nullable();
            $table->smallInteger('mail_port')->nullable();
            $table->string('mail_username', 50)->nullable();
            $table->string('mail_password', 50)->nullable();
            $table->string('mail_encryption', 30)->nullable();
            $table->string('mail_from', 50)->nullable();
            $table->tinyInteger('is_hyperlocal')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('need_delivery_service')->default(0)->comment('0 - no, 1 - yes');
            $table->tinyInteger('need_dispacher_ride')->default(0)->comment('0 - no, 1 - yes');
            $table->string('delivery_service_key', 100)->nullable();
            $table->string('primary_color', 10)->nullable();
            $table->string('secondary_color', 10)->nullable();
            $table->string('dispatcher_key', 100)->nullable();
            $table->timestamps();
        });

        Schema::table('client_preferences', function (Blueprint $table) {
            $table->foreign('client_code')->references('code')->on('clients')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('map_provider')->references('id')->on('map_providers')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('sms_provider')->references('id')->on('sms_providers')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('web_template_id')->references('id')->on('templates')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('app_template_id')->references('id')->on('templates')->onUpdate('cascade')->onDelete('set null');
            
             $table->index('is_hyperlocal');
             $table->index('need_delivery_service');
             $table->index('mail_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_preferences');
    }
}
