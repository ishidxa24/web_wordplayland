<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPlayland</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .game-card-simple {
            @apply flex items-center p-4 rounded-lg shadow-md text-white transform hover:scale-105 transition-transform duration-300;
        }
        .animated-clouds {
            background-color: #87CEEB; /* Sky Blue */
        }
    </style>
</head>
<body class="font-sans antialiased animated-clouds">

    {{-- ▼▼▼ PENAMBAHAN 1: State 'showTitleModal' ditambahkan di sini ▼▼▼ --}}
    <div x-data="{
            showLoginModal: false,
            showTitleModal: {{ session()->has('new_title_notification') ? 'true' : 'false' }},
            showLogoutModal: false, // <-- State untuk modal logout
            volume: 0.5,
            isMuted: false,
            musicEl: null,
            navOpen: false,
            init() {
                this.musicEl = document.getElementById('main-menu-music');
                if (this.musicEl) {
                    this.musicEl.src = '{{ asset('audio/music/main-menu.mp3') }}';
                    this.updateVolume();
                    this.playMusic();
                }
                this.$watch('volume', () => this.updateVolume());
                this.$watch('isMuted', () => this.updateVolume());
                window.addEventListener('pageshow', (event) => {
                    if (event.persisted && this.musicEl && this.musicEl.paused) {
                        this.playMusic();
                    }
                });
            },
            updateVolume() {
                if (this.musicEl) { this.musicEl.volume = this.isMuted ? 0 : this.volume; }
            },
            toggleMute() {
                this.isMuted = !this.isMuted;
            },
            playMusic() {
                if (this.musicEl) {
                    this.musicEl.play().catch(() => document.body.addEventListener('click', () => this.musicEl.play(), { once: true }));
                }
            }
         }"
         x-init="init()"
         @keydown.escape.window="showLoginModal = false; showTitleModal = false; showLogoutModal = false">

        @auth
        <div class="fixed top-0 right-0 p-4 md:p-6 z-20">
            <div class="relative">
                <div class="hidden sm:flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="bg-white/70 backdrop-blur-sm px-4 py-2 rounded-md font-semibold text-gray-600 hover:text-gray-900 shadow transition">Dashboard</a>
                    <a href="{{ route('scoreboard') }}" class="bg-white/70 backdrop-blur-sm px-4 py-2 rounded-md font-semibold text-gray-600 hover:text-gray-900 shadow transition">Scoreboard</a>

                    {{-- ▼▼▼ PERBAIKAN 2: Tombol logout sekarang memicu modal ▼▼▼ --}}
                    <button @click="showLogoutModal = true" type="button" class="bg-red-500/80 hover:bg-red-600/80 px-4 py-2 rounded-md font-semibold text-white shadow transition">
                        Log Out
                    </button>
                </div>
                <div class="sm:hidden">
                    <button @click="navOpen = !navOpen" class="bg-white/70 backdrop-blur-sm p-2 rounded-md shadow focus:outline-none">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!navOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="navOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="navOpen" x-cloak @click.outside="navOpen = false" class="sm:hidden fixed top-20 right-4 z-20">
            <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-lg p-2 space-y-1 w-48">
                <a href="{{ route('dashboard') }}" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-200 rounded-md">Dashboard</a>
                <a href="{{ route('scoreboard') }}" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-200 rounded-md">Scoreboard</a>
                <div class="border-t border-gray-200 my-1"></div>
                {{-- ▼▼▼ PERBAIKAN 3: Link logout sekarang memicu modal ▼▼▼ --}}
                <a href="#" @click.prevent="showLogoutModal = true; navOpen = false" class="block w-full text-left px-4 py-2 text-base font-medium text-red-700 hover:bg-red-100 rounded-md">
                    Log Out
                </a>
            </div>
        </div>
        @endauth

        <div class="min-h-screen flex flex-col items-center justify-center p-4">
            <div class="bg-yellow-400 text-center py-4 px-8 rounded-2xl shadow-lg mb-6 border-4 border-white">
                <h1 class="text-4xl md:text-5xl font-bold text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    Welcome to WordPlayland!
                </h1>
            </div>
            <p class="text-xl font-semibold text-gray-700 mb-6">
                Choose a fun challenge:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full max-w-lg">
                @auth
                <a href="{{ route('games.jumble-jungle') }}" class="game-card-simple bg-blue-400">
                    <div class="text-4xl mr-4">🧙</div>
                    <span class="font-bold text-xl">Word Wizard</span>
                </a>
                <a href="{{ route('games.match-and-win') }}" class="game-card-simple bg-purple-500">
                    <div class="text-4xl mr-4">✨</div>
                    <span class="font-bold text-xl">Match & Win!</span>
                </a>
                <a href="{{ route('games.build-a-sentence') }}" class="game-card-simple bg-green-500">
                    <div class="text-4xl mr-4">🍌</div>
                    <span class="font-bold text-xl">Build-a-Sentence</span>
                </a>
                <a href="{{ route('games.exercise-hub') }}" class="game-card-simple bg-red-500">
                    <div class="text-4xl mr-4">📚</div>
                    <span class="font-bold text-xl">Exercise</span>
                </a>
                @else
                <button @click.prevent="showLoginModal = true" class="game-card-simple bg-blue-400">
                    <div class="text-4xl mr-4">🧙</div>
                    <span class="font-bold text-xl">Word Wizard</span>
                </button>
                <button @click.prevent="showLoginModal = true" class="game-card-simple bg-purple-500">
                    <div class="text-4xl mr-4">✨</div>
                    <span class="font-bold text-xl">Match & Win!</span>
                </button>
                <button @click.prevent="showLoginModal = true" class="game-card-simple bg-green-500">
                    <div class="text-4xl mr-4">🍌</div>
                    <span class="font-bold text-xl">Build-a-Sentence</span>
                </button>
                <button @click.prevent="showLoginModal = true" class="game-card-simple bg-red-500">
                    <div class="text-4xl mr-4">📚</div>
                    <span class="font-bold text-xl">Exercise</span>
                </button>
                @endauth
            </div>
        </div>

        @guest
        <div x-show="showLoginModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div @click.outside="showLoginModal = false" class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center">
                <h3 class="text-2xl font-bold mb-4">Login to Play</h3>
                <p class="text-gray-600 mb-6">Please log in or register to start playing and save your progress.</p>
                <a href="{{ route('login') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-lg transition duration-300 mb-4">
                    Log In / Register
                </a>
            </div>
        </div>
        @endguest

        {{-- ▼▼▼ PENAMBAHAN 2: Blok HTML untuk modal notifikasi ▼▼▼ --}}
        @if (session('new_title_notification'))
        <div x-show="showTitleModal" x-cloak class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
            <div @click.outside="showTitleModal = false" class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-2xl p-8 w-full max-w-md text-center transform transition-all" x-show="showTitleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="text-6xl mb-4 animate-bounce">🏆</div>
                <h3 class="text-3xl font-bold mb-2 text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">Title Unlocked!</h3>
                <div class="text-lg text-white/90 mb-6">
                    {!! session('new_title_notification') !!}
                </div>
                <button @click="showTitleModal = false" class="bg-white/30 hover:bg-white/50 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-300">
                    Awesome!
                </button>
            </div>
        </div>
        @endif

        {{-- Modal Konfirmasi Logout --}}
        @auth
        <div x-show="showLogoutModal" x-cloak class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
            <div @click.outside="showLogoutModal = false" class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center">
                <h3 class="text-2xl font-bold mb-2 text-gray-800">Log Out</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to log out?</p>
                <div class="flex justify-center space-x-4">
                    <button @click="showLogoutModal = false" class="px-8 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold text-gray-800 transition">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-8 py-2 rounded-lg bg-red-600 hover:bg-red-700 font-semibold text-white transition">
                            Yes, Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth

        <div class="fixed bottom-4 left-4 z-20 flex items-center space-x-3 bg-white/70 backdrop-blur-sm p-2 rounded-full shadow-lg">
            <button @click="toggleMute()" class="focus:outline-none">
                <svg x-show="!isMuted && volume > 0" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                <svg x-show="isMuted || volume == 0" x-cloak class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15zM17 14l2-2m0 0l2-2m-2 2l-2 2m2-2l2 2"></path></svg>
            </button>
            <input type="range" min="0" max="1" step="0.01" x-model="volume" class="w-24 h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer">
        </div>
    </div>

    <audio id="main-menu-music" loop></audio>

</body>
</html>
