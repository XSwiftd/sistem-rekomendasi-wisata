<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->unsignedBigInteger('wisata_id')->primary(); // Menyesuaikan ID dari dataset CSV
            $table->string('nama_wisata');
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->string('kategori');
            $table->text('tags');
            $table->text('deskripsi');
            $table->string('estimasi_biaya');
            $table->enum('tingkat_kesulitan', ['Mudah', 'Sedang', 'Sulit']);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};