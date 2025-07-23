<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Build-a-Sentence - WordPlayland</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body, html { overflow: auto; height: 100%; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased font-sans">

    <div class="bg-green-300 min-h-screen flex flex-col items-center p-4 sm:p-6"
         x-data="{
            sentenceBank: {
                easy: ['I LOVE YOU', 'HE IS TALL', 'THEY PLAY BALL', 'SHE EATS RICE', 'WE ARE HAPPY'],
                medium: ['THE CAT IS SLEEPING', 'MY FATHER IS A DOCTOR', 'THEY GO TO SCHOOL', 'SHE READS A BOOK', 'HE KICKS THE BALL'],
                hard: ['THE SUN IS SHINING BRIGHTLY', 'BIRDS CAN FLY HIGH IN THE SKY', 'I AM LEARNING ENGLISH NOW', 'MY SISTER IS VERY BEAUTIFUL']
            },
            sentenceCategories: {
                'I LOVE YOU': 'SIMPLE S-V-O', 'HE IS TALL': 'SIMPLE S-V-O (3rd Person)', 'THEY PLAY BALL': 'SIMPLE S-V-O', 'SHE EATS RICE': 'SIMPLE S-V-O (3rd Person)', 'WE ARE HAPPY': 'SIMPLE S-V-O', 'THE CAT IS SLEEPING': 'PRESENT CONTINUOUS', 'MY FATHER IS A DOCTOR': 'SIMPLE S-V-O', 'THEY GO TO SCHOOL': 'SIMPLE S-V-O', 'SHE READS A BOOK': 'SIMPLE S-V-O (3rd Person)', 'HE KICKS THE BALL': 'SIMPLE S-V-O (3rd Person)', 'THE SUN IS SHINING BRIGHTLY': 'PRESENT CONTINUOUS', 'BIRDS CAN FLY HIGH IN THE SKY': 'SIMPLE MODAL VERBS', 'I AM LEARNING ENGLISH NOW': 'PRESENT CONTINUOUS', 'MY SISTER IS VERY BEAUTIFUL': 'SIMPLE S-V-O'
            },
            categoryExplanations: {
                'SIMPLE S-V-O': 'Pola kalimat paling dasar adalah Subjek (I, They) + Kata Kerja (Love, Play) + Objek (You, Ball).',
                'SIMPLE S-V-O (3rd Person)': 'Tips: Untuk subjek tunggal seperti He atau She, kata kerjanya sering ditambah akhiran -s atau -es.',
                'PRESENT CONTINUOUS': 'Tips: Pola ini (is/am/are + kata kerja-ing) digunakan untuk kegiatan yang sedang terjadi sekarang.',
                'SIMPLE MODAL VERBS': 'Tips: Kata seperti Can (bisa) biasanya diikuti oleh kata kerja bentuk pertama (seperti fly).'
            },
            availableSentences: [],
            currentSentence: '',
            currentCategory: '',
            shuffledWords: [],
            playerAnswer: [],
            score: 0,
            lives: 3,
            feedback: '',
            isRoundOver: false,
            showScoreModal: false,
            finalMessage: '',
            wrongAnswers: [],
            levelsUnlocked: ['easy'],
            volume: 0.5,

            initGame() {
                this.score = 0;
                this.lives = 3;
                this.wrongAnswers = [];
                this.showScoreModal = false;
                this.levelsUnlocked = ['easy'];
                this.availableSentences = [...this.sentenceBank.easy];
                document.getElementById('main-menu-music')?.pause();
                this.playGameMusic();
                this.nextSentence();
            },
            nextSentence() {
                if (this.score >= 30 && !this.levelsUnlocked.includes('medium')) { this.availableSentences.push(...this.sentenceBank.medium); this.levelsUnlocked.push('medium'); }
                if (this.score >= 60 && !this.levelsUnlocked.includes('hard')) { this.availableSentences.push(...this.sentenceBank.hard); this.levelsUnlocked.push('hard'); }
                if (this.availableSentences.length === 0) { this.endGame('win'); return; }
                this.isRoundOver = false;
                this.feedback = '';
                this.playerAnswer = [];
                const randomIndex = Math.floor(Math.random() * this.availableSentences.length);
                this.currentSentence = this.availableSentences.splice(randomIndex, 1)[0];
                this.currentCategory = this.sentenceCategories[this.currentSentence] || 'GENERAL';
                this.shuffledWords = this.currentSentence.split(' ').sort(() => Math.random() - 0.5).map((word, index) => ({ id: `${Date.now()}-${index}`, word: word, used: false }));
            },
            selectWord(wordObj) {
                if (wordObj.used) return;
                this.playerAnswer.push(wordObj);
                wordObj.used = true;
            },
            removeWord(index) {
                const removedWordObj = this.playerAnswer.splice(index, 1)[0];
                if (removedWordObj) { removedWordObj.used = false; }
            },
            submitAnswer() {
                if (this.isRoundOver || this.playerAnswer.length === 0) return;
                const finalAnswer = this.playerAnswer.map(w => w.word).join(' ');
                if (finalAnswer === this.currentSentence) {
                    this.feedback = 'Excellent! ✅';
                    this.score += 15;
                    this.playSoundEffect('correct.mp3');
                } else {
                    this.feedback = 'Not quite!';
                    this.lives--;
                    this.wrongAnswers.push({ sentence: this.currentSentence, category: this.currentCategory });
                    this.playSoundEffect('wrong.mp3');
                }
                this.isRoundOver = true;
                if (this.lives <= 0) { this.endGame('lose'); }
                else if (this.score >= 100) { this.endGame('win'); }
            },
            endGame(status) {
                document.getElementById('game-music')?.pause();
                this.finalMessage = (status === 'win') ? 'Congratulations!' : 'Game Over!';
                this.playSoundEffect(status === 'win' ? 'win.mp3' : 'lose.mp3');
                if (this.score > 0) { this.saveScoreToServer(); }
                this.showScoreModal = true;
            },
            saveScoreToServer() {
                const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                fetch('{{ route('game.finish') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ game_mode: 'Build-a-Sentence', score: this.score })
                }).then(res => res.json()).then(data => console.log('Score saved:', data.message)).catch(err => console.error('Error saving score:', err));
            },
            playSoundEffect(fileName) {
                try {
                    const sfx = new Audio(`{{ asset('audio/sfx') }}/${fileName}`);
                    sfx.volume = this.volume;
                    sfx.play().catch(e => console.warn('Could not play SFX: ' + fileName, e));
                } catch (e) { console.error('Error playing sound effect.', e); }
            },
            playGameMusic() {
                const music = document.getElementById('game-music');
                if (music) {
                    music.src = '{{ asset('audio/music/build-a-sentence.mp3') }}';
                    music.volume = this.volume;
                    music.currentTime = 0;
                    music.play().catch(() => document.body.addEventListener('click', () => music.play(), { once: true }));
                }
            },
            updateVolume() {
                const music = document.getElementById('game-music');
                if(music) music.volume = this.volume;
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
                    <svg class="w-6 h-6 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-bold text-green-800">Build-a-Sentence</h1>
            </div>
        </header>

        <main x-show="!showScoreModal" x-cloak class="bg-green-50/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg w-full max-w-3xl mx-auto text-center">
            <div class="flex justify-between items-center mb-6 text-green-800 font-semibold">
                <span>Score: <span x-text="score"></span></span>
                <div class="flex items-center space-x-1">
                    <span>Lives:</span>
                    <template x-for="i in lives">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                    </template>
                </div>
            </div>
            <p class="font-semibold text-gray-600 mb-2">Arrange the words to form a sentence:</p>
            <div class="bg-white p-4 rounded-lg shadow-inner mb-6 min-h-[72px] flex justify-center items-center flex-wrap gap-2">
                <template x-for="(wordObj, index) in playerAnswer" :key="wordObj.id">
                    <button @click="removeWord(index)" class="bg-blue-200 text-blue-800 font-bold py-2 px-4 rounded-lg shadow-sm"><span x-text="wordObj.word"></span></button>
                </template>
            </div>
            <div class="flex justify-center items-center flex-wrap gap-3 mb-6">
                <template x-for="wordObj in shuffledWords" :key="wordObj.id">
                    <button @click="selectWord(wordObj)" :disabled="wordObj.used" :class="{ 'opacity-25 cursor-not-allowed': wordObj.used }" class="bg-white text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transform hover:scale-110 transition"><span x-text="wordObj.word"></span></button>
                </template>
            </div>
            <div x-show="feedback" x-text="feedback" class="font-bold my-4 h-6" :class="{ 'text-green-600': feedback.includes('Excellent'), 'text-red-500': feedback.includes('Not quite') }"></div>
            <div class="mt-4">
                <button x-show="!isRoundOver" @click="submitAnswer()" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">Check My Answer</button>
                <button x-show="isRoundOver && lives > 0" @click="nextSentence()" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">Next Sentence</button>
            </div>
        </main>

        <div x-show="showScoreModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg text-center">
                <h3 class="text-3xl font-bold mb-4 text-green-800" x-text="finalMessage"></h3>
                <p class="text-xl text-gray-700 mb-2">Your Final Score:</p>
                <p class="text-6xl font-bold text-amber-500 mb-6" x-text="score"></p>
                <template x-if="wrongAnswers.length > 0">
                    <div class="text-left mt-4 border-t pt-4">
                        <h4 class="font-bold text-lg mb-2 text-gray-700">Review & Suggestions</h4>
                        <ul class="space-y-3 max-h-40 overflow-y-auto">
                            <template x-for="answer in wrongAnswers" :key="answer.sentence">
                                <li class="text-left p-3 bg-gray-50 rounded-lg border">
                                    <p class="text-sm text-gray-500">Correct Sentence:</p>
                                    <p class="font-bold text-lg text-green-600" x-text="answer.sentence"></p>
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full mt-2" x-text="answer.category"></span>
                                    <p class="text-sm italic text-blue-700 mt-1" x-text="categoryExplanations[answer.category] || ''"></p>
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
