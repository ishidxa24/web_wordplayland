<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pronunciation Practice - WordPlayland</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body, html {
            height: 100%;
        }
        body {
            overflow-y: auto;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased font-sans">

    <div class="bg-red-200 min-h-screen flex flex-col items-center p-4 sm:p-6"
         x-data="{
            // BANK SOAL BARU dengan kesulitan bertingkat
            questionBank: [
                { image: 'apple.png', sentence: 'She eats an apple.', type: 'PRESENT_TENSE', tip: 'For he/she/it, remember to add -s to the verb, like \'eats\'.' },
                { image: 'football.png', sentence: 'They play football.', type: 'PRESENT_TENSE', tip: 'For I/you/we/they, the verb does not need an -s.' },
                { image: 'car.png', sentence: 'The car is fast.', type: 'PRESENT_TENSE', tip: 'Use \'is\' for a single object.' },
                { image: 'cat.png', sentence: 'The cat slept on the mat.', type: 'PAST_TENSE', tip: 'The past tense of \'sleep\' is \'slept\'.' },
                { image: 'book.png', sentence: 'He read a book yesterday.', type: 'PAST_TENSE', tip: 'The past tense of \'read\' is spelled the same, but pronounced \'red\'.' },
                { image: 'plane.png', sentence: 'The plane flew over the city.', type: 'PAST_TENSE', tip: 'The past tense of \'fly\' is \'flew\'.' },
                { image: 'basketball.png', sentence: 'We will play basketball tomorrow.', type: 'FUTURE_TENSE', tip: 'For future plans, use \'will\' before the main verb.' },
                { image: 'sun.png', sentence: 'The sun will shine later.', type: 'FUTURE_TENSE', tip: 'Use \'will\' to talk about future events.' },
                { image: 'bus.png', sentence: 'She is going to take the bus.', type: 'FUTURE_TENSE', tip: 'Use \'is going to\' for future intentions.' },
                { image: 'dog.png', sentence: 'The dog will bark loudly.', type: 'FUTURE_TENSE', tip: 'Remember to use \'will\' for future actions.' }
            ],

            // Variabel state permainan
            availableQuestions: [],
            currentQuestion: {},
            transcript: '...',
            isRecording: false,
            feedback: 'Click the mic and read the sentence!',
            suggestion: '',
            isCorrect: null,
            score: 0,
            round: 0,
            totalRounds: 10,
            showScoreModal: false,

            // Fungsi untuk memulai/mengulang permainan
            initGame() {
                this.availableQuestions = [...this.questionBank].sort(() => Math.random() - 0.5);
                this.score = 0;
                this.round = 0;
                this.showScoreModal = false;
                this.nextQuestion();
            },

            // Fungsi untuk memulai ronde pertanyaan baru
            nextQuestion() {
                if (this.round >= this.totalRounds || this.availableQuestions.length === 0) {
                    this.endGame(); return;
                }
                this.round++;
                this.feedback = 'Click the mic and read the sentence!';
                this.suggestion = '';
                this.transcript = '...';
                this.isCorrect = null;
                this.currentQuestion = this.availableQuestions.pop();
            },

            // Fungsi untuk mendengarkan pengucapan yang benar (Text-to-Speech)
            listenToSentence() {
                if (!this.currentQuestion.sentence) return;
                const utterance = new SpeechSynthesisUtterance(this.currentQuestion.sentence);
                utterance.lang = 'en-US';
                speechSynthesis.speak(utterance);
            },

            // Fungsi untuk mulai merekam suara pengguna (Speech-to-Text)
            startRecording() {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (!SpeechRecognition) {
                    alert('Sorry, your browser does not support Speech Recognition.');
                    return;
                }
                const recognition = new SpeechRecognition();
                recognition.lang = 'en-US';
                this.isRecording = true;
                this.feedback = 'Listening...';
                this.transcript = '...';
                this.isCorrect = null;
                this.suggestion = '';
                recognition.start();
                recognition.onresult = (event) => {
                    const speechResult = event.results[0][0].transcript;
                    this.transcript = speechResult;
                    this.checkPronunciation(speechResult);
                };
                recognition.onend = () => { this.isRecording = false; };
                recognition.onerror = () => {
                    this.isRecording = false;
                    this.feedback = 'Error or no speech detected. Please try again.';
                };
            },

            // Fungsi untuk mengecek hasil pengucapan
            checkPronunciation(spokenText) {
                const cleanOriginal = this.currentQuestion.sentence.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, '').toLowerCase();
                const cleanSpoken = spokenText.toLowerCase();

                if (cleanSpoken === cleanOriginal) {
                    this.feedback = 'Excellent Pronunciation! ✅';
                    this.isCorrect = true;
                    this.suggestion = '';
                    this.score += 10;
                } else {
                    this.feedback = 'Not quite right. Check the tip below!';
                    this.isCorrect = false;
                    this.suggestion = this.currentQuestion.tip || 'Try to match the sentence exactly.';
                }
            },

            // Fungsi untuk mengakhiri permainan
            endGame() {
                this.showScoreModal = true;
            }
         }"
         x-init="initGame()">

        {{-- Header --}}
        <div class="w-full max-w-3xl mx-auto mb-8 flex-shrink-0">
            {{-- ▼▼▼ PERBAIKAN PADA STRUKTUR HEADER ▼▼▼ --}}
            <div class="relative flex items-center justify-center h-10">
                <a href="{{ route('games.exercise-hub') }}" class="absolute left-0 bg-white/50 p-2 rounded-full transform hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                {{-- Judul sekarang berada di tengah dan responsif --}}
                <h1 class="w-full text-center text-3xl sm:text-4xl font-bold text-red-800 px-12">Pronunciation Practice</h1>
            </div>
        </div>

        {{-- Tampilan Permainan Utama --}}
        <main x-show="!showScoreModal" x-cloak class="bg-red-50/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg w-full max-w-3xl mx-auto text-center mb-auto">

            <div class="flex justify-between items-center mb-6 text-red-800 font-semibold">
                <span>Score: <span x-text="score"></span></span>
                <span>Question: <span x-text="round"></span>/<span x-text="totalRounds"></span></span>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-inner mb-6 h-48 sm:h-56 flex items-center justify-center">
                <img :src="'/images/cards/' + currentQuestion.image" :alt="currentQuestion.sentence" class="max-w-full max-h-full object-contain" onerror="this.src='https://placehold.co/200x200/fecaca/b91c1c?text=Image'"/>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-inner mb-6 flex items-center justify-center space-x-4">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800" x-text="currentQuestion.sentence"></h2>
                <button @click="listenToSentence()" title="Listen to pronunciation" class="p-2 sm:p-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a1 1 0 011 1v1.414l3.293-3.293a1 1 0 111.414 1.414L12.414 7H14a1 1 0 110 2h-1.586l3.293 3.293a1 1 0 11-1.414 1.414L11 9.414V11a1 1 0 11-2 0V4a1 1 0 011-1z M4 5a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm1 4a1 1 0 100 2h1a1 1 0 100-2H5z"></path></svg>
                </button>
            </div>

            <div class="my-4 sm:my-8">
                <button @click="startRecording()" :disabled="isRecording" class="w-20 h-20 sm:w-24 sm:h-24 bg-red-500 text-white rounded-full shadow-lg flex items-center justify-center mx-auto transform hover:scale-110 transition-transform disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="!isRecording" class="w-10 h-10 sm:w-12 sm:h-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 017 8a1 1 0 10-2 0 7.001 7.001 0 006 6.93V17H9a1 1 0 100 2h2a1 1 0 100-2h-1v-2.07z" clip-rule="evenodd"></path></svg>
                    <div x-show="isRecording" class="w-10 h-10 sm:w-12 sm:h-12 animate-pulse"><svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 017 8a1 1 0 10-2 0 7.001 7.001 0 006 6.93V17H9a1 1 0 100 2h2a1 1 0 100-2h-1v-2.07z" clip-rule="evenodd"></path></svg></div>
                </button>
                <p class="mt-2 text-sm font-semibold h-5" :class="{ 'text-green-600': isCorrect === true, 'text-red-600': isCorrect === false }" x-text="feedback"></p>
                <p x-show="suggestion" class="mt-2 text-md text-blue-700 italic" x-text="suggestion"></p>
            </div>

            <div class="bg-white/50 p-4 rounded-lg min-h-[50px]">
                <p class="text-gray-500 text-sm">You said:</p>
                <p class="font-semibold text-xl" x-text="transcript"></p>
            </div>

            <div class="mt-6">
                <button @click="nextQuestion()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">
                    Next Question
                </button>
            </div>
        </main>

        {{-- POP-UP SCOREBOARD --}}
        <div x-show="showScoreModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md text-center">
                <h3 class="text-3xl font-bold mb-4 text-red-800">Exercise Complete!</h3>
                <p class="text-xl text-gray-700 mb-2">Your Final Score:</p>
                <p class="text-6xl font-bold text-amber-500 mb-6" x-text="score"></p>
                <button @click="initGame()" class="mt-8 w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg shadow-md transition duration-300">Play Again</button>
            </div>
        </div>
    </div>

</body>
</html>
