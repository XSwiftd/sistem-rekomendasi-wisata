<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary(); // Menyesuaikan ID dari dataset CSV
            $table->string('nama_pengguna');
            $table->integer('usia');
            $table->string('jenis_kelamin');
            $table->string('domisili');
            $table->string('minat_kategori');
            $table->string('preferensi_anggaran');
            $table->string('preferensi_kesulitan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};