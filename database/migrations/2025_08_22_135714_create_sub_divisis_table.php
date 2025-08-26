<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sub_divisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis')->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();

            $table->unique(['divisi_id', 'nama']); // biar gak dobel nama sub divisi di divisi yg sama
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_divisis');
    }
};
