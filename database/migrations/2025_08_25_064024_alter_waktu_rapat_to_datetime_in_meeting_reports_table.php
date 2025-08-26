<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('meeting_reports', function (Blueprint $table) {
            // ubah varchar -> datetime (boleh nullable kalau ada data lama yang belum rapi)
            $table->dateTime('waktu_rapat')->nullable()->change();

            // opsional: index biar filter cepat
            $table->index('waktu_rapat');
            $table->index('divisi');
            $table->index('sub_divisi');
        });
    }

    public function down(): void
    {
        Schema::table('meeting_reports', function (Blueprint $table) {
            $table->string('waktu_rapat', 255)->nullable(false)->change();
            $table->dropIndex(['waktu_rapat']);
            $table->dropIndex(['divisi']);
            $table->dropIndex(['sub_divisi']);
        });
    }
};
