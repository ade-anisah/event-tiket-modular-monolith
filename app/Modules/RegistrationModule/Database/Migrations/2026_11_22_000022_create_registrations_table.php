<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('participants');
            // Relasi logis ke events dan ticket_types (modul lain), tanpa foreign key.
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('ticket_type_id')->index();
            $table->string('kode_pendaftaran', 30)->unique();
            $table->unsignedSmallInteger('jumlah');
            $table->unsignedInteger('harga_satuan_snapshot');
            $table->unsignedInteger('total');
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};