<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 60)->unique();
            $table->string('phone_number', 24)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('type')->default(0)->comment('1 for buyer, 2 for seller');
            $table->tinyInteger('status')->default(0)->comment('0 - pending, 1 - active, 2 - blocked, 3 - inactive');
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->string('auth_token')->nullable();
            $table->string('system_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {

            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('set null');

            $table->index('phone_number');
            $table->index('type');
            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
