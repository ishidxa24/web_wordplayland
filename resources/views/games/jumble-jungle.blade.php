<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Word Wizard - WordPlayland</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body, html {
            overflow: hidden;
            height: 100%;
            font-family: 'Poppins', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased">

    <div class="bg-yellow-300 min-h-screen flex flex-col items-center p-4 sm:p-6"
         x-data="{
            wordBank: {
                easy: { 'NOUNS': ['CAT', 'DOG', 'SUN', 'SKY', 'BALL'], 'VERBS': ['RUN', 'EAT', 'SEE', 'FLY', 'CRY'] },
                medium: { 'NOUNS': ['APPLE', 'TABLE', 'WATER', 'SCHOOL', 'HOUSE'], 'VERBS': ['DRINK', 'SLEEP', 'WRITE', 'READ', 'JUMP'], 'ADJECTIVES': ['HAPPY', 'SMALL', 'BLUE', 'FAST', 'GOOD'] },
                hard: { 'NOUNS': ['COMPUTER', 'BICYCLE', 'KNOWLEDGE', 'LIBRARY'], 'VERBS': ['UNDERSTAND', 'QUESTION', 'CREATE', 'IMAGINE'], 'ADJECTIVES': ['BEAUTIFUL', 'DIFFICULT', 'INTERESTING'] }
            },
            wordToCategoryMap: {},
            availableWords: [],
            currentWord: '',
            currentCategory: '',
            shuffledWord: '',
            playerAnswer: '',
            score: 0,
            lives: 3,
            feedback: '',
            wrongAnswers: [],
            isRoundOver: false,
            showScoreModal: false,
            finalMessage: '',
            levelsUnlocked: ['easy'],
            volume: 0.5,

            initGame() {
                this.setupWordMap();
                this.score = 0;
                this.lives = 3;
                this.wrongAnswers = [];
                this.showScoreModal = false;
                this.levelsUnlocked = ['easy'];
                this.availableWords = [...this.wordBank.easy.NOUNS, ...this.wordBank.easy.VERBS];
                document.getElementById('main-menu-music')?.pause();
                this.playGameMusic();
                this.nextWord();
            },
            nextWord() {
                this.unlockLevels();
                if (this.availableWords.length === 0) { this.endGame('win'); return; }
                this.isRoundOver = false;
                this.feedback = '';
                this.playerAnswer = '';
                const randomIndex = Math.floor(Math.random() * this.availableWords.length);
                this.currentWord = this.availableWords.splice(randomIndex, 1)[0];
                this.currentCategory = this.findCategoryForWord(this.currentWord);
                this.shuffledWord = this.currentWord.split('').sort(() => Math.random() - 0.5).join('');
                this.$nextTick(() => document.getElementById('answerInput')?.focus());
            },
            submitAnswer() {
                if (this.isRoundOver) return;
                if (this.playerAnswer.toUpperCase().trim() === this.currentWord) { this.handleCorrectAnswer(); }
                else { this.handleIncorrectAnswer(); }
                this.isRoundOver = true;
            },
            endGame(status) {
                document.getElementById('game-music')?.pause();
                this.finalMessage = (status === 'win') ? 'Congratulations, You Win!' : 'Game Over! Keep practicing!';
                this.playSoundEffect(status === 'win' ? 'win.mp3' : 'lose.mp3');
                if (this.score > 0) { this.saveScoreToServer(); }
                this.showScoreModal = true;
            },
            handleCorrectAnswer() {
                this.playSoundEffect('correct.mp3');
                this.feedback = 'Correct! 🎉';
                this.score += 10;
                if (this.score >= 100) { this.endGame('win'); }
            },
            handleIncorrectAnswer() {
                this.playSoundEffect('wrong.mp3');
                this.feedback = 'Incorrect! The word was ' + this.currentWord + '.';
                this.lives--;
                this.wrongAnswers.push({ correct: this.currentWord, category: this.currentCategory });
                if (this.lives <= 0) { this.endGame('lose'); }
            },
            unlockLevels() {
                if (this.score >= 50 && !this.levelsUnlocked.includes('medium')) { this.availableWords.push(...Object.values(this.wordBank.medium).flat()); this.levelsUnlocked.push('medium'); }
                if (this.score >= 80 && !this.levelsUnlocked.includes('hard')) { this.availableWords.push(...Object.values(this.wordBank.hard).flat()); this.levelsUnlocked.push('hard'); }
            },
            setupWordMap() {
                this.wordToCategoryMap = {};
                for (const level in this.wordBank) {
                    for (const category in this.wordBank[level]) {
                        this.wordBank[level][category].forEach(word => { this.wordToCategoryMap[word] = category; });
                    }
                }
            },
            findCategoryForWord(word) { return this.wordToCategoryMap[word] || 'GENERAL'; },
            saveScoreToServer() {
                const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                fetch('{{ route('game.finish') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ game_mode: 'Word Wizard', score: this.score })
                }).then(res => res.json()).then(data => console.log(data.message)).catch(err => console.error(err));
            },
            playSoundEffect(fileName) {
                const sfx = new Audio('{{ asset('audio/sfx') }}/' + fileName);
                sfx.volume = this.volume;
                sfx.play().catch(e => console.warn('SFX play failed:', e));
            },
            playGameMusic() {
                const music = document.getElementById('game-music');
                if (music) {
                    music.src = '{{ asset('audio/music/word-wizard.mp3') }}';
                    music.volume = this.volume;
                    music.currentTime = 0;
                    music.play().catch(() => document.body.addEventListener('click', () => music.play(), { once: true }));
                }
            },
            updateVolume() {
                const music = document.getElementById('game-music');
                if (music) music.volume = this.volume;
            }
         }"
         x-init="initGame()">

        <audio id="game-music" loop></audio>
        <div class="fixed bottom-4 left-4 z-20 flex items-center space-x-2 bg-white/50 backdrop-blur-sm p-2 rounded-full shadow-lg">
            <span class="text-xl">🔊</span>
            <input type="range" min="0" max="1" step="0.01" x-model="volume" @input="updateVolume()" class="w-24 h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer">
        </div>

        <header class="w-full max-w-2xl mx-auto mb-6">
            <div class="relative flex items-center justify-center">
                <a href="{{ route('welcome') }}" class="absolute left-0 bg-white/50 p-2 rounded-full transform hover:scale-110 transition-transform" aria-label="Back to home">
                    <svg class="w-6 h-6 text-yellow-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-bold text-yellow-800">Word Wizard</h1>
            </div>
        </header>

        {{-- ▼▼▼ KONTEN GAME YANG HILANG DIKEMBALIKAN DI SINI ▼▼▼ --}}
        <main x-show="!showScoreModal" class="bg-yellow-50/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg w-full max-w-2xl mx-auto text-center">
            <div class="flex justify-between items-center mb-6 text-yellow-800 font-semibold">
                <span>Score: <span x-text="score" class="font-bold"></span></span>
                <span class="bg-yellow-200 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded-full" x-text="currentCategory"></span>
                <div class="flex items-center space-x-1">
                    <span>Lives:</span>
                    <template x-for="i in lives">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                    </template>
                </div>
            </div>

            <p class="font-semibold text-gray-600 mb-2">Unscramble the letters:</p>
            <div class="bg-white p-4 rounded-lg shadow-inner mb-6">
                <p class="text-4xl font-bold tracking-widest text-gray-700" x-text="shuffledWord"></p>
            </div>

            <form @submit.prevent="submitAnswer()">
                <label for="answerInput" class="font-semibold text-gray-600 mb-2 block">Your answer:</label>
                <input id="answerInput" type="text" x-model="playerAnswer" :disabled="isRoundOver" class="w-full text-center text-2xl font-bold tracking-widest p-3 rounded-lg border-2 border-gray-300 focus:border-amber-500 focus:ring-amber-500 transition" placeholder="Type here..." autocomplete="off" autocapitalize="off">
            </form>

            <div x-show="feedback" x-text="feedback" class="font-bold my-4 h-6" :class="{ 'text-green-600': feedback.includes('Correct'), 'text-red-500': feedback.includes('Incorrect') }"></div>

            <div class="mt-4">
                <button x-show="!isRoundOver" @click="submitAnswer()" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">Submit</button>
                <button x-show="isRoundOver && lives > 0" @click="nextWord()" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">Next Word</button>
            </div>
        </main>

        <div x-show="showScoreModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md text-center">
                <h3 class="text-3xl font-bold mb-4 text-yellow-800" x-text="finalMessage"></h3>
                <p class="text-xl text-gray-700 mb-2">Your Final Score:</p>
                <p class="text-6xl font-bold text-amber-500 mb-4" x-text="score"></p>

                <template x-if="finalMessage.includes('Game Over') && wrongAnswers.length > 0">
                    <div class="text-left mt-6 border-t pt-4">
                        <h4 class="font-bold text-lg mb-2 text-gray-700">Review & Suggestions</h4>
                        <p class="text-sm text-gray-500 mb-3">You seem to have difficulty with these topics:</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <template x-for="category in Array.from(new Set(wrongAnswers.map(wa => wa.category)))">
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full" x-text="category"></span>
                            </template>
                        </div>
                        <h5 class="font-semibold text-base text-gray-700 mt-4">Let's check the mistakes:</h5>
                        <ul class="max-h-24 overflow-y-auto text-sm text-gray-600 space-y-1 mt-2">
                            <template x-for="answer in wrongAnswers">
                                <li>- The correct word was <strong class="text-green-600" x-text="answer.correct"></strong>.</li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div class="mt-8 w-full flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('welcome') }}" class="w-full sm:w-1/2 bg-slate-600 hover:bg-slate-700 text-white font-bold py-3 rounded-lg shadow-md transition">Back to Menu</a>
                    <button @click="initGame()" class="w-full sm:w-1/2 bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg shadow-md transition">Play Again</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
