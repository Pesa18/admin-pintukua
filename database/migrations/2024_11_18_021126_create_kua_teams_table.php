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
        Schema::create('kua_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kua')->nullable()->constrained('profile_companies', 'id_kua')->onDelete('set null'); // Foreign key ke kuas
            $table->foreignId('id_employee')->nullable()->constrained('employees')->onDelete('set null'); // Foreign key ke kuas
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key ke users
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kua_user');
    }
};
