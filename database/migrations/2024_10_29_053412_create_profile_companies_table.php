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
        Schema::create('profile_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->integer('id_kua');
            $table->string('address');
            $table->string('coordinates');
            $table->integer('id_kecamatan');
            $table->integer('id_kabupaten');
            $table->integer('id_provinsi');
            $table->string('contact');
            $table->string('facebook')->nullable();
            $table->string('email');
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_companies');
    }
};
