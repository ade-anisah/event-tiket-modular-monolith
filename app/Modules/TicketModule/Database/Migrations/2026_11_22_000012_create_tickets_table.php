<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_type_id')->constrained('ticket_types');
            // Relasi logis ke registrations (RegistrationModule) dan users (Shared Kernel).
            $table->unsignedBigInteger('registration_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('kode', 20)->unique();
            $table->string('status', 20)->default('aktif');
            $table->unsignedInteger('harga_snapshot');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
