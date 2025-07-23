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
        Schema::create('game_scores', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap skor yang tercatat

            // Menghubungkan skor ini ke seorang pemain (user)
            // Ini mengasumsikan Anda memiliki tabel 'users' bawaan Laravel
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Menyimpan jenis game yang dimainkan
            $table->string('game_mode'); // Contoh: 'Word Wizard', 'Match & Win!'

            // Skor yang didapat dari game tersebut
            $table->integer('score')->default(0);

            // Waktu kapan game ini dimainkan (SANGAT PENTING untuk scoreboard mingguan)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_scores');
    }
};
