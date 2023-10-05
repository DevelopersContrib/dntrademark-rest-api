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
        Schema::create('domain_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id');
            $table->string('keyword')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('status_label')->nullable();
            $table->date('status_date')->nullable();
            $table->string('status_definition')->nullable();
            $table->date('filing_date')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('abandonment_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_items');
    }
};
