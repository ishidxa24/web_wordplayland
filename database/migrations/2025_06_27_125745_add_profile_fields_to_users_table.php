<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom level, defaultnya 1 untuk user baru
            $table->integer('level')->default(1)->after('email');

            // Menambahkan kolom title, defaultnya 'Newbie' untuk user baru
            $table->string('title')->default('Newbie')->after('level');

            // Menambahkan kolom experience points (xp), defaultnya 0
            $table->integer('experience_points')->default(0)->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus semua kolom jika migrasi di-rollback
            $table->dropColumn(['level', 'title', 'experience_points']);
        });
    }
};
