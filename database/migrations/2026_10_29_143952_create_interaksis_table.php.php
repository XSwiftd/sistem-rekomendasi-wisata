<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('interaksi_id')->primary(); // Primary key sesuai CSV
            
            // Foreign Keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wisata_id');
            
            $table->enum('jenis_interaksi', ['rating', 'bookmark']);
            $table->decimal('rating', 2, 1)->nullable(); // Misal: 4.5
            $table->text('ulasan')->nullable();
            $table->timestamp('tanggal_interaksi')->useCurrent();
            $table->timestamps();

            // Constraint Foreign Key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('wisata_id')->references('wisata_id')->on('wisatas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interaksis');
    }
};