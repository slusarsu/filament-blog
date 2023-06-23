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
        Schema::create('adm_form_data_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adm_form_data_id')->constrained(
                table: 'adm_form_data', indexName: 'adm_form_data_id'
            )->onDelete('cascade');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm_form_data_items');
    }
};
