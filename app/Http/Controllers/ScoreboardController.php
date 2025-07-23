<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameScore;
use App\Models\User; // <-- PENTING: import User untuk mengambil nama
use Illuminate\Support\Facades\DB;

class ScoreboardController extends Controller
{
    /**
     * Menampilkan halaman scoreboard.
     */
    public function index()
    {
        // 1. Mengambil dan mengolah data skor
        $scoreboardData = User::query()
            // Pilih kolom nama dari tabel users
            ->select('users.name',
                // Buat kolom baru 'total_score' yang isinya adalah jumlah dari kolom score
                DB::raw('SUM(game_scores.score) as total_score')
            )
            // Gabungkan dengan tabel game_scores dimana id user sama
            ->join('game_scores', 'users.id', '=', 'game_scores.user_id')
            // Kelompokkan hasilnya berdasarkan id dan nama user
            ->groupBy('users.id', 'users.name')
            // Urutkan dari total_score tertinggi ke terendah
            ->orderByDesc('total_score')
            // Ambil hasilnya
            ->get();

        // 2. Mengirim data yang sudah diolah ke sebuah view
        // 'scoreboard' adalah nama file view (scoreboard.blade.php)
        // 'players' adalah nama variabel yang akan kita gunakan di view
        return view('scoreboard', ['players' => $scoreboardData]);
    }
}
