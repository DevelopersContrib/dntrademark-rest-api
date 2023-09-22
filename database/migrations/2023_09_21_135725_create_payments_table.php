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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->string('payment_type')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_payment_status')->nullable();
            $table->text('stripe_receipt_url')->nullable();
            $table->text('description')->nullable();
            $table->text('result_json')->nullable();
            $table->text('mode_key')->nullable();
            $table->timestamp('date_paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
