<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id('bookmark_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wisata_id');
            $table->timestamp('created_at')->useCurrent();

            // Unique constraint agar 1 user tidak bisa bookmark wisata yang sama 2x
            $table->unique(['user_id', 'wisata_id']);

            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('wisata_id')->references('wisata_id')->on('wisatas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};