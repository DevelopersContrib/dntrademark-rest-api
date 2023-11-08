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
        Schema::create('domains_items_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('keyword')->nullable();
            $table->string('owner_label')->nullable();
            $table->string('legal_entity_type')->nullable();
            $table->string('name')->nullable();
            $table->string('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains_items_owners');
    }
};
