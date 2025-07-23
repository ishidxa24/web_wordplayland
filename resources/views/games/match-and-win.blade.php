<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Match & Win - WordPlayland</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ▼▼▼ PERBAIKAN: Mengizinkan scroll vertikal ▼▼▼ */
        body, html {
            overflow-y: auto;
            scroll-behavior: smooth;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased font-sans">

    <div class="bg-purple-300 min-h-screen flex flex-col items-center p-4 sm:p-6"
         x-data="{
            riddleBank: {
                easy: [
                    { riddle: 'I am a sweet, red fruit that keeps the doctor away.', answer: 'APPLE', image: 'apple.png', choices: ['apple.png', 'car.png', 'sun.png'] },
                    { riddle: 'I have four wheels and people drive me on the road.', answer: 'CAR', image: 'car.png', choices: ['car.png', 'lamp.png', 'apple.png'] },
                    { riddle: 'You can read me. I have many pages and a cover.', answer: 'BOOK', image: 'book.png', choices: ['book.png', 'basketball.png', 'star.png'] },
                    { riddle: 'I am a yellow fruit that monkeys love.', answer: 'BANANA', image: 'banana.png', choices: ['banana.png', 'book.png', 'moon.png'] }
                ],
                medium: [
                    { riddle: 'I am a big yellow vehicle that takes children to school.', answer: 'BUS', image: 'bus.png', choices: ['bus.png', 'car.png', 'plane.png'] },
                    { riddle: 'I am a domestic animal and I say meow.', answer: 'CAT', image: 'cat.png', choices: ['cat.png', 'dog.png', 'bird.png'] },
                    { riddle: 'I shine brightly in the sky during the day.', answer: 'SUN', image: 'sun.png', choices: ['sun.png', 'moon.png', 'star.png'] },
                    { riddle: 'I give light in a dark room. You can turn me on and off.', answer: 'LAMP', image: 'lamp.png', choices: ['lamp.png', 'sun.png', 'star.png'] }
                ],
                hard: [
                    { riddle: 'I have wings and can fly high in the sky, but I am not a bird.', answer: 'PLANE', image: 'plane.png', choices: ['plane.png', 'bird.png', 'car.png'] },
                    // ▼▼▼ PERBAIKAN SINTAKS: 'man\'s' agar tidak error ▼▼▼
                    { riddle: 'I am a man\'s best friend and I can bark.', answer: 'DOG', image: 'dog.png', choices: ['dog.png', 'cat.png', 'tree.png'] },
                    { riddle: 'I am a big orange ball used in a sport with a hoop.', answer: 'BASKETBALL', image: 'basketball.png', choices: ['basketball.png', 'football.png', 'volleyball.png'] },
                    { riddle: 'I am a place with many very tall buildings.', answer: 'SKYSCRAPERS', image: 'skyscrapers.png', choices: ['skyscrapers.png', 'house.png', 'tree.png'] }
                ]
            },
            availableRiddles: [],
            currentRiddle: {},
            shuffledChoices: [],
            playerTextAnswer: '',
            playerImageAnswer: '',
            score: 0,
            lives: 3,
            timeLeft: 30,
            timer: null,
            feedback: '',
            isRoundOver: false,
            showScoreModal: false,
            finalMessage: '',
            wrongAnswers: [],
            consecutiveCorrect: 0,
            unlockedLevels: ['easy'],
            volume: 0.5,

            initGame() {
                this.availableRiddles = [...this.riddleBank.easy].sort(() => Math.random() - 0.5);
                this.score = 0;
                this.lives = 3;
                this.consecutiveCorrect = 0;
                this.unlockedLevels = ['easy'];
                this.wrongAnswers = [];
                this.showScoreModal = false;
                document.getElementById('main-menu-music')?.pause();
                this.playGameMusic();
                this.nextRiddle();
            },
            nextRiddle() {
                if (this.consecutiveCorrect >= 2 && !this.unlockedLevels.includes('medium')) { this.availableRiddles.push(...this.riddleBank.medium); this.unlockedLevels.push('medium'); this.feedback = 'Great! Medium riddles unlocked!'; }
                if (this.consecutiveCorrect >= 4 && !this.unlockedLevels.includes('hard')) { this.availableRiddles.push(...this.riddleBank.hard); this.unlockedLevels.push('hard'); this.feedback = 'Amazing! Hard riddles unlocked!'; }
                if (this.availableRiddles.length === 0) { this.endGame('win'); return; }
                this.isRoundOver = false;
                if (!this.feedback.includes('unlocked')) { this.feedback = ''; }
                this.playerTextAnswer = '';
                this.playerImageAnswer = '';
                this.timeLeft = 30;
                const randomIndex = Math.floor(Math.random() * this.availableRiddles.length);
                this.currentRiddle = this.availableRiddles.splice(randomIndex, 1)[0];
                this.shuffledChoices = [...this.currentRiddle.choices].sort(() => Math.random() - 0.5);
                this.startTimer();
                this.$nextTick(() => document.getElementById('answerInput')?.focus());
            },
            startTimer() {
                if (this.timer) clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (this.timeLeft > 0 && !this.isRoundOver) { this.timeLeft--; }
                    else if (this.timeLeft <= 0 && !this.isRoundOver) {
                        this.feedback = 'Time\'s Up!'; // <-- Perbaikan sintaks
                        this.handleIncorrectAnswer(false);
                    }
                }, 1000);
            },
            handleIncorrectAnswer(playSfx = true) {
                if (playSfx) this.playSoundEffect('wrong.mp3');
                this.isRoundOver = true;
                this.consecutiveCorrect = 0;
                this.lives--;
                this.wrongAnswers.push(this.currentRiddle);
                clearInterval(this.timer);
                if (this.lives <= 0) { this.endGame('lose'); }
                else { setTimeout(() => this.nextRiddle(), 2000); }
            },
            selectImage(imageName) {
                if (this.isRoundOver) return;
                this.playerImageAnswer = imageName;
            },
            submitAnswer() {
                if (this.isRoundOver) return;
                clearInterval(this.timer);
                this.isRoundOver = true;
                const isTextCorrect = this.playerTextAnswer.toUpperCase().trim() === this.currentRiddle.answer;
                const isImageCorrect = this.playerImageAnswer === this.currentRiddle.image;
                if (isTextCorrect && isImageCorrect) {
                    this.playSoundEffect('correct.mp3');
                    this.feedback = 'Perfect! +5s bonus time!';
                    this.score += 10 + this.timeLeft;
                    this.consecutiveCorrect++;
                    if (this.score >= 200) { this.endGame('win'); }
                    else { setTimeout(() => { this.timeLeft += 5; this.nextRiddle(); }, 1500); }
                } else {
                    this.feedback = 'Oh no! The correct answer was ' + this.currentRiddle.answer;
                    this.handleIncorrectAnswer();
                }
            },
            endGame(status) {
                clearInterval(this.timer);
                document.getElementById('game-music')?.pause();
                this.finalMessage = (status === 'win') ? 'Congratulations, You Win!' : 'Game Over!';
                this.playSoundEffect(status === 'win' ? 'win.mp3' : 'lose.mp3');
                if (this.score > 0) { this.saveScoreToServer(); }
                this.showScoreModal = true;
            },
            saveScoreToServer() {
                const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                fetch('{{ route('game.finish') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ game_mode: 'Match and Win', score: this.score })
                }).then(res => res.json()).then(data => console.log('Score saved:', data.message)).catch(error => console.error('Error sending score:', error));
            },
            playSoundEffect(fileName) {
                const sfx = new Audio('{{ asset('audio/sfx') }}/' + fileName);
                sfx.volume = this.volume;
                sfx.play().catch(e => console.warn('SFX play failed', e));
            },
            playGameMusic() {
                const music = document.getElementById('game-music');
                if (music) {
                    music.src = '{{ asset('audio/music/match-and-win.mp3') }}';
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

        <header class="w-full max-w-3xl mx-auto mb-6">
            <div class="relative flex items-center justify-center">
                <a href="{{ route('welcome') }}" class="absolute left-0 bg-white/50 p-2 rounded-full transform hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-bold text-purple-800">English Riddle</h1>
            </div>
        </header>

        <main x-show="!showScoreModal" class="bg-purple-50/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg w-full max-w-3xl mx-auto text-center">
            <div class="flex justify-between items-center mb-6 text-purple-800 font-semibold">
                <span>Score: <span x-text="score"></span></span>
                <span class="text-lg">Time: <span x-text="timeLeft.toString().padStart(2, '0')" class="font-bold text-xl"></span>s</span>
                <div class="flex items-center space-x-1">
                    <span>Lives:</span>
                    <template x-for="i in lives"><svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg></template>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-inner mb-6 min-h-[80px] flex items-center justify-center">
                <p class="text-lg md:text-xl font-semibold text-gray-700" x-text="currentRiddle.riddle"></p>
            </div>
            <p class="font-semibold text-gray-600 mb-2">1. Choose the correct picture:</p>
            <div class="grid grid-cols-3 gap-4 mb-6">
                <template x-for="choice in shuffledChoices" :key="choice">
                    <div @click="selectImage(choice)" :class="{ 'border-4 border-yellow-400 scale-105': playerImageAnswer === choice, 'border-2 border-transparent': playerImageAnswer !== choice }" class="bg-white p-2 rounded-lg shadow-md cursor-pointer transition-all duration-200">
                         <img :src="'/images/cards/' + choice" :alt="choice" class="w-full h-full object-contain aspect-square" onerror="this.src='https://placehold.co/150x150/e9d5ff/6b21a8?text=Image'"/>
                    </div>
                </template>
            </div>
            <form @submit.prevent="submitAnswer()">
                <label for="answerInput" class="font-semibold text-gray-600 mb-2 block">2. Type the answer:</label>
                <input id="answerInput" type="text" x-model="playerTextAnswer" :disabled="isRoundOver" class="w-full text-center text-2xl font-bold p-3 rounded-lg border-2 border-gray-300 focus:border-purple-500 focus:ring-purple-500 transition" placeholder="Type your answer here..." autocomplete="off" autocapitalize="off">
            </form>
            <div x-show="feedback" x-text="feedback" class="font-bold my-4 h-6" :class="{ 'text-green-600': feedback.includes('Perfect'), 'text-red-500': feedback.includes('Oh no') || feedback.includes('Time') }"></div>
            <div class="mt-4">
                <button x-show="!isRoundOver" @click="submitAnswer()" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">Submit Answer</button>
            </div>
        </main>

        <div x-show="showScoreModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md text-center">
                <h3 class="text-3xl font-bold mb-4 text-purple-800" x-text="finalMessage"></h3>
                <p class="text-xl text-gray-700 mb-2">Your Final Score:</p>
                <p class="text-6xl font-bold text-amber-500 mb-6" x-text="score"></p>
                <template x-if="wrongAnswers.length > 0">
                    <div class="text-left mt-4 border-t pt-4">
                        <h4 class="font-bold text-lg mb-2 text-gray-700">Review Your Mistake(s)</h4>
                        <ul class="space-y-2 max-h-32 overflow-y-auto">
                            <template x-for="riddle in wrongAnswers" :key="riddle.riddle">
                                <li class="text-sm text-gray-600">
                                    For the riddle "<span class="italic" x-text="riddle.riddle"></span>", the correct answer was <strong class="text-green-600" x-text="riddle.answer"></strong>.
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>
                <div class="mt-8 w-full flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('welcome') }}" class="w-full sm:w-1/2 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-lg shadow-md transition">Back to Menu</a>
                    <button @click="initGame()" class="w-full sm:w-1/2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow-md transition">Play Again</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
