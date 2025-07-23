<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Tambahkan ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; // Tidak kita perlukan

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'title',
        'experience_points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mendefinisikan relasi bahwa satu User memiliki banyak GameScore.
     */
    public function gameScores(): HasMany
    {
        return $this->hasMany(GameScore::class);
    }

    /**
     * Accessor "pintar" untuk Title.
     * Fungsi ini akan otomatis berjalan setiap kali kita memanggil $user->title.
     * ▼▼▼ TAMBAHKAN FUNGSI BARU INI ▼▼▼
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Ambil daftar title dari file config
                $score_titles = config('levels.score_titles', []);
                // Hitung total skor pengguna saat ini juga
                $totalScore = $this->gameScores()->sum('score');

                // Loop untuk mencari title yang sesuai (dari tertinggi ke terendah)
                foreach ($score_titles as $score_needed => $title) {
                    if ($totalScore >= $score_needed) {
                        return $title; // Kembalikan title pertama yang skornya tercapai
                    }
                }

                return 'Newbie'; // Default title jika tidak ada yang cocok
            },
        );
    }
}
