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
        Schema::create('BannerApp', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('image_path'); // Path to banner image (stored in public/storage or S3)
            $table->string('link')->nullable(); // Link associated with the banner
            $table->boolean('is_active'); // Indicates if the banner is active
            $table->timestamp('start_at')->nullable(); // When the banner becomes active
            $table->timestamp('end_at')->nullable(); // When the banner should be deactivated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BannerApp');
    }
};
