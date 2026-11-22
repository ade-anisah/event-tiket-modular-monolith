<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->string('slug', 180)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 150);
            $table->dateTime('mulai_at');
            $table->dateTime('selesai_at');
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('peserta_terdaftar')->default(0);
            // Relasi logis ke users (Shared Kernel), tanpa foreign key.
            $table->unsignedBigInteger('created_by')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
