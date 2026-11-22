<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            // Relasi logis ke users (Shared Kernel), tanpa foreign key.
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('nama_lengkap', 100);
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
