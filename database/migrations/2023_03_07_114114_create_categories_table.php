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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('order')->default(0);
            $table->string('post_type')->default('post');
            $table->text('content')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_text_keys')->nullable();
            $table->string('seo_description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->string('lang')->nullable();
            $table->softDeletesDatetime();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
