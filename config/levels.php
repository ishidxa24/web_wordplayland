<?php

// File ini menyimpan aturan untuk title berdasarkan total skor.

return [

    /*
    |--------------------------------------------------------------------------
    | Score-based Titles
    |--------------------------------------------------------------------------
    |
    | Daftar ini dipetakan dari SKOR MINIMAL yang dibutuhkan untuk
    | mendapatkan sebuah title.
    | Penting: Urutkan dari skor TERTINGGI ke TERENDAH.
    |
    */
    'score_titles' => [
        // Skor Minimal => 'Nama Title'
        20000 => 'WordPlayland God',
        18000 => 'Linguistic Lord',
        16000 => 'Grammar Guru',
        14000 => 'Sentence Samurai',
        12000 => 'Word Dominator',
        10000 => 'Legend',
        7500  => 'Champion',
        5000  => 'Virtuoso',
        2500  => 'Prodigy',
        1500  => 'Lexicographer',
        1000  => 'Scribe',
        750   => 'Grandmaster',
        500   => 'Master',
        400   => 'Expert',
        300   => 'Adept',
        200   => 'Explorer',
        100   => 'Word Scout',
        50    => 'Apprentice',
        0     => 'Newbie',
    ],

    // 'xp_base' sudah tidak kita gunakan lagi dalam sistem baru ini.
];
