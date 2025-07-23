<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoreboard - WordPlayland!</title>
    {{-- Memuat Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Memuat Font dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-fredoka {
            font-family: 'Fredoka One', cursive;
        }
        /* Latar belakang awan yang sama dengan game Anda */
        body {
            background-color: #87CEEB; /* Sky Blue */
            background-image:
                url('https://www.transparentpng.com/thumb/clouds/aJ5LIJ-clouds-transparent-background.png'),
                url('https://www.transparentpng.com/thumb/clouds/T2kC4S-clouds-transparent-picture.png');
            background-repeat: no-repeat;
            background-position: 10% 10%, 90% 80%;
            background-size: 300px, 250px;
        }
    </style>
</head>
<body class="bg-sky-200 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-3xl mx-auto bg-white/70 backdrop-blur-sm rounded-2xl shadow-2xl p-6 md:p-8">

        {{-- Judul Halaman --}}
        <div class="text-center mb-8">
            <h1 class="font-fredoka text-4xl md:text-5xl text-yellow-500" style="text-shadow: 2px 2px 0 #00000040;">
                Papan Peringkat
            </h1>
            <p class="text-gray-600 mt-2">Lihat siapa juaranya!</p>
        </div>

        {{-- Tabel Scoreboard --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                <thead class="bg-blue-400">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold text-white text-left">
                            Peringkat
                        </th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold text-white text-left">
                            Nama Pemain
                        </th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold text-white text-left">
                            Total Skor
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    {{-- Di sinilah keajaiban Blade terjadi --}}
                    {{-- Kita melakukan looping pada data $players yang dikirim dari Controller --}}
                    @forelse ($players as $player)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="whitespace-nowrap px-4 py-3 font-bold text-gray-800 text-center">
                                {{-- $loop->iteration akan otomatis menampilkan nomor urut: 1, 2, 3, ... --}}
                                #{{ $loop->iteration }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                {{-- Menampilkan nama pemain --}}
                                {{ $player->name }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-blue-600 font-bold">
                                {{-- Menampilkan total skor pemain --}}
                                {{ number_format($player->total_score) }}
                            </td>
                        </tr>
                    @empty
                        {{-- Bagian ini akan tampil jika tidak ada data skor sama sekali --}}
                        <tr>
                            <td colspan="3" class="text-center py-8 text-gray-500">
                                Belum ada skor tercatat. Jadilah yang pertama!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tombol Kembali --}}
        <div class="text-center mt-8">
            {{-- ▼▼▼ TOMBOL INI DIPERBAIKI ▼▼▼ --}}
            <a href="{{ route('welcome') }}" class="inline-block rounded-lg bg-yellow-400 hover:bg-yellow-500 px-8 py-3 text-lg font-bold text-white shadow-lg transition">
                Kembali ke Menu
            </a>
        </div>

    </div>

</body>
</html>
