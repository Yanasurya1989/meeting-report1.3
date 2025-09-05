<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis')->onDelete('cascade');
            $table->foreignId('sub_divisi_id')->constrained('sub_divisis')->onDelete('cascade');
            $table->string('hari'); // contoh: Senin, Selasa, dst
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable(); // kalau ada jam selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_meetings');
    }
};
