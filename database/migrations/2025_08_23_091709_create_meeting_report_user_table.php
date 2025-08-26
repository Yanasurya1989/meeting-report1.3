<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meeting_report_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_report_id')->constrained('meeting_reports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['meeting_report_id', 'user_id']); // cegah dobel peserta di rapat yang sama
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_report_user');
    }
};
