<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Users
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->string('nama_pengguna');
            $table->integer('usia');
            $table->string('jenis_kelamin');
            $table->string('domisili');
            $table->string('minat_kategori');
            $table->string('preferensi_anggaran');
            $table->string('preferensi_kesulitan');
            $table->timestamps();
        });

        // 2. Tabel Sessions (Diperlukan oleh Laravel Session Driver)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};