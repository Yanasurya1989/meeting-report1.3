<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peserta_sub_divisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sub_divisi_id')->constrained('sub_divisis')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'sub_divisi_id']); // biar gak dobel assign
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_sub_divisi');
    }
};
