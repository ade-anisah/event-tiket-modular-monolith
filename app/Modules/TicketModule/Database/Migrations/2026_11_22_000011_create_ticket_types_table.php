<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            // Relasi logis ke events (EventModule), tanpa foreign key.
            $table->unsignedBigInteger('event_id')->index();
            $table->string('nama', 80);
            $table->unsignedInteger('harga');
            $table->unsignedInteger('kuota');
            $table->unsignedInteger('terjual')->default(0);
            $table->unsignedTinyInteger('maks_per_pembelian')->default(4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};