<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedInteger('file_size');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('collection_name')->default('default');
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('collection_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
