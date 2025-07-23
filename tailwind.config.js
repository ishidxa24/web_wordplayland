import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            // KODE BARU DITAMBAHKAN DI SINI
            colors: {
              'wordplay-bg': '#A0D2EB',      // Biru langit untuk background
              'wordplay-banner': '#FFD700', // Kuning untuk banner welcome
              'wordplay-wizard': '#5D9CEC', // Biru untuk tombol Word Wizard
              'wordplay-match': '#7B61FF',  // Ungu untuk tombol Match & Win
              'wordplay-sentence': '#9CD33B',// Hijau-kuning untuk Build-a-Sentence
              'wordplay-books': '#FF6B6B',  // Merah-oranye untuk tombol ke-4
              'wordplay-darkblue-text': '#2A4D69', // Biru tua untuk teks
            },
        },
    },

    plugins: [forms],
};
