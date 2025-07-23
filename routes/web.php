<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ScoreboardController;
use App\Http\Controllers\PageController; // Pastikan controller ini ada
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute utama (/) sekarang mengarah ke halaman start-game.
Route::get('/', function () {
    return view('start-game');
})->name('start');

// Rute untuk halaman menu utama (welcome)
Route::get('/welcome', function () {
    // Cek jika pengguna sudah login untuk menampilkan data di welcome page
    if (Auth::check()) {
        $user = Auth::user();
        return view('welcome', ['user' => $user]);
    }
    // Jika tamu, tampilkan halaman welcome biasa.
    return view('welcome');
})->name('welcome');


// Rute yang memerlukan login (grup middleware 'auth')
Route::middleware('auth')->group(function () {

    // Rute dashboard tetap memanggil ProfileController
    Route::get('/dashboard', [ProfileController::class, 'show'])->name('dashboard');

    // Rute untuk halaman Scoreboard
    Route::get('/scoreboard', [ScoreboardController::class, 'index'])->name('scoreboard');

    // Rute untuk profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Grup untuk rute-rute permainan dengan nama yang konsisten
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/jumble-jungle', [GameController::class, 'jumbleJungle'])->name('jumble-jungle');
        Route::get('/match-and-win', [GameController::class, 'matchAndWin'])->name('match-and-win');
        Route::get('/build-a-sentence', [GameController::class, 'buildASentence'])->name('build-a-sentence');
        Route::get('/exercise-hub', [GameController::class, 'exerciseHub'])->name('exercise-hub');
        Route::get('/learn', [GameController::class, 'learnMaterials'])->name('learn');
        Route::get('/pronunciation', [GameController::class, 'pronunciationPractice'])->name('pronunciation');
    });

    // Rute untuk menyimpan skor dari semua game
    Route::post('/game/finish', [GameController::class, 'finishGame'])->name('game.finish');
});

// Memuat rute otentikasi standar Laravel (login, register, dll.)
require __DIR__.'/auth.php';
