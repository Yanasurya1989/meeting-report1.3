<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meeting_reports', function (Blueprint $table) {
            $table->id();
            $table->text('notulen'); // disimpan dalam bentuk json/serialize
            $table->string('divisi')->nullable();
            $table->string('sub_divisi')->nullable();
            $table->json('peserta')->nullable();
            $table->longText('capture_image')->nullable(); // base64 foto
            $table->string('waktu_rapat'); // format hari, tanggal, jam
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_reports');
    }
};
