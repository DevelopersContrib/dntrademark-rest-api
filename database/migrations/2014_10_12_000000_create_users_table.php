<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('package_id')->default(1);
            $table->date('package_expiry')->nullable();
            $table->tinyInteger('is_admin')->default('0');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_code')->nullable();
            $table->tinyInteger('is_onboarding')->default('0');
            $table->tinyInteger('allow_email')->default('1');
            $table->tinyInteger('allow_sms')->default('0');
            $table->string('sms_code')->nullable();
            $table->string('sms_number')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
