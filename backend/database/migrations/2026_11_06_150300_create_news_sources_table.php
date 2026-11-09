<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('feed_url', 512);
            $table->string('website_url', 2048)->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_trusted')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_imported_at')->nullable();
            $table->timestamps();
            $table->unique('feed_url');
            $table->index(['is_active', 'is_trusted']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
