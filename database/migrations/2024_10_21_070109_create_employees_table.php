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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lengkap pegawai
            $table->string('email')->unique(); // Email pegawai\
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->string('phone')->nullable(); // Nomor telepon, opsional
            $table->string('nik')->unique(); // NIK (Nomor Induk Kependudukan)
            $table->boolean('is_kepala')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['PNS', 'PPPK', 'Non-ASN']); // Status kepegawaian (ASN atau Non-ASN)
            $table->string('grade')->nullable(); // golongan pegawai
            $table->string('position')->nullable(); // Jabatan pegawai
            $table->string('nip')->unique()->nullable(); // NIP untuk ASN (opsional untuk non-ASN)
            $table->foreignId('id_kua')->nullable()->constrained('profile_companies', 'id_kua')->onDelete('set null')->onUpdate("cascade"); // Unit kerja pegawai
            $table->date('date_of_birth'); // Tanggal lahir pegawai
            $table->date('date_of_joining'); // Tanggal bergabung dengan organisasi
            $table->string('gender')->nullable(); // Jenis kelamin pegawai
            $table->string('avatar')->nullable(); // Foto pegawai, opsional
            $table->string('address')->nullable(); // Alamat pegawai, opsional
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
