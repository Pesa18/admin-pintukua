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
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->uuid()->autoIncrement();
            $table->string('name'); // Nama pengguna
            $table->string('email')->unique(); // Email yang unik
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable(); // Kolom untuk menyimpan nomor telepon (opsional)
            $table->string('nik')->unique()->nullable(); // Kolom untuk menyimpan NIK yang harus unik
            $table->string('password'); // Password terenkripsi
            $table->string('avatar')->nullable(); // Kolom untuk menyimpan URL avatar (opsional)
            $table->rememberToken(); // Kolom untuk "remember me" token
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
