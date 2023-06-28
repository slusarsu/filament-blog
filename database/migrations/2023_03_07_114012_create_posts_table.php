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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('post');
            $table->text('short')->nullable();
            $table->text('content')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_text_keys')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('post_type')->default('post');
            $table->boolean('is_enabled')->default(true);
            $table->string('lang')->nullable();
            $table->string('translation_code')->nullable();
            $table->softDeletesDatetime();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
