<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // cek apakah ada foreign key role_id, kalau ada hapus dulu
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']); // hapus foreign key
                $table->dropColumn('role_id');    // baru drop kolom
            }

            // tambahkan kolom divisi_id
            if (!Schema::hasColumn('users', 'divisi_id')) {
                $table->unsignedBigInteger('divisi_id')->nullable()->after('id');
                $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'divisi_id')) {
                $table->dropForeign(['divisi_id']);
                $table->dropColumn('divisi_id');
            }

            // restore role_id kalau rollback
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            }
        });
    }
};
