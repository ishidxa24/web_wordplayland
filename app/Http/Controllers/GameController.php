<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\GameScore;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{

    public function jumbleJungle(): View
    {
        return view('games.jumble-jungle');
    }


    public function matchAndWin(): View
    {
        return view('games.match-and-win');
    }


    public function buildASentence(): View
    {
        return view('games.build-a-sentence');
    }


    public function exerciseHub(): View
    {
        return view('games.exercise-hub');
    }


    public function learnMaterials(): View
    {
        return view('games.learn-materials');
    }


    public function pronunciationPractice(): View
    {
        return view('games.pronunciation-practice');
    }


    public function finishGame(Request $request): JsonResponse
    {
        // 1. Validasi data yang dikirim dari frontend
        $validated = $request->validate([
            'game_mode' => 'required|string|max:255',
            'score' => 'required|integer|min:0',
        ]);

        // 2. Dapatkan pengguna yang sedang login
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);
        }

        // 3. Simpan catatan skor baru ke tabel 'game_scores' untuk riwayat
        GameScore::create([
            'user_id' => $user->id,
            'game_mode' => $validated['game_mode'],
            'score' => $validated['score'],
        ]);

        // 4. Hitung total skor pengguna dari semua permainan
        $totalScore = $user->gameScores()->sum('score');

        // 5. Logika untuk menentukan Title baru dari file config
        $score_titles = config('levels.score_titles', []);
        $newTitle = 'Newbie'; // Default title
        foreach ($score_titles as $score_needed => $title) {
            if ($totalScore >= $score_needed) {
                $newTitle = $title;
                break; // Hentikan loop saat title yang cocok ditemukan
            }
        }

        // ▼▼▼ PERBAIKAN UTAMA ADA DI SINI ▼▼▼

        // 6. Perbarui kolom 'experience_points' dan 'title' pada user
        $user->experience_points = $totalScore; // Simpan total skor ke kolom XP
        $user->title = $newTitle;             // Simpan title baru

        // 7. Simpan semua perubahan ke database
        $user->save();

        // 8. Kirim respons berhasil
        return response()->json([
            'status' => 'success',
            'message' => 'Score and Title have been updated successfully.',
            'xp_gained' => $validated['score']
        ]);
    }
}
