<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id(); // id auto increment
            $table->uuid('user_id'); // UUID sesuai dengan tabel user_accounts
            $table->string('otp'); // Menyimpan kode OTP
            $table->dateTime('expires_at'); // Waktu kadaluwarsa OTP
            $table->boolean('is_used')->default(false); // Status apakah sudah digunakan
            $table->timestamps(); // created_at & updated_at otomatis

            // Foreign key ke user_accounts
            $table->foreign('user_id')
                ->references('uuid')
                ->on('user_accounts')
                ->onDelete('cascade') // Jika user dihapus, OTP juga dihapus
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
