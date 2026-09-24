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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            // UUID untuk QR Code statis identitas (Digital Pass)
            $table->uuid('qr_code_id')->unique()->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 25);
            $table->boolean('is_reward_claimed')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
