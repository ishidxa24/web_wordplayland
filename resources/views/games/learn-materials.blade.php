<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Materials - WordPlayland</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tone/14.7.77/Tone.js"></script>

    <style>
        body, html { overflow-y: auto; scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .prose ul > li::before { content: '✓'; color: #ef4444; font-weight: bold; margin-right: 0.5rem; }
    </style>
</head>
<body class="antialiased font-sans">

    {{-- ▼▼▼ PERBAIKAN: x-data sekarang menggunakan kutip ganda, dan semua string di dalamnya menggunakan kutip tunggal ▼▼▼ --}}
    <div class="bg-red-200 min-h-screen p-4 sm:p-6"
         x-data="{
            currentView: 'menu',
            materialBank: {
                nouns: {
                    icon: '🍎', menuTitle: 'Nouns (Kata Benda)', menuDesc: 'Latihan menyusun kalimat dengan kata benda.',
                    title: 'Apa Itu Noun (Kata Benda)?',
                    explanation: `<p><strong>Noun</strong> adalah kata yang kita gunakan untuk memberi nama pada sesuatu. Anggap saja Noun itu seperti 'label nama' untuk semua hal di sekitar kita.</p><p class='mt-2'>Noun bisa digunakan untuk menamai:</p><ul><li><strong>Person (Orang):</strong> seperti <code>teacher</code>, <code>doctor</code>, <code>boy</code>.</li><li><strong>Place (Tempat):</strong> seperti <code>school</code>, <code>house</code>, <code>beach</code>.</li><li><strong>Thing (Benda):</strong> seperti <code>book</code>, <code>ball</code>, <code>apple</code>.</li></ul>`,
                    examples: [ 'The <strong>teacher</strong> reads a <strong>book</strong>.', 'My <strong>cat</strong> is sleeping in the <strong>house</strong>.' ],
                    exercises: [
                        { image: 'apple.png', sentence: 'I EAT AN APPLE' },
                        { image: 'book.png', sentence: 'SHE READS A BOOK' },
                        { image: 'car.png', sentence: 'THE CAR IS FAST' }
                    ]
                },
                verbs: {
                    icon: '🏃‍♂️', menuTitle: 'Verbs (Kata Kerja)', menuDesc: 'Latihan menyusun kalimat dengan kata kerja.',
                    title: 'Apa Itu Verb (Kata Kerja)?',
                    explanation: `<p><strong>Verb</strong> adalah kata yang menunjukkan sebuah <strong>aksi (action)</strong> atau sebuah keadaan. Tanpa Verb, sebuah kalimat tidak akan lengkap. Verb adalah 'mesin' dari sebuah kalimat!</p>`,
                    examples: [ 'Birds <strong>fly</strong> high.', 'He <strong>is</strong> a boy.' ],
                    exercises: [
                        { image: 'dog.png', sentence: 'THE DOG RUNS FAST' },
                        { image: 'cat.png', sentence: 'THE CAT IS SLEEPING' },
                        { image: 'plane.png', sentence: 'THE PLANE FLIES HIGH' }
                    ]
                },
                adjectives: {
                    icon: '😊', menuTitle: 'Adjectives (Kata Sifat)', menuDesc: 'Latihan menyusun kalimat dengan kata sifat.',
                    title: 'Apa Itu Adjective (Kata Sifat)?',
                    explanation: `<p><strong>Adjective</strong> adalah kata yang tugasnya <strong>menjelaskan</strong> atau memberi sifat pada sebuah Noun (kata benda). Adjective membuat kalimat kita lebih berwarna dan detail.</p>`,
                    examples: [ 'The <strong>big</strong> elephant.', 'She has a <strong>red</strong> apple.' ],
                    exercises: [
                        { image: 'sun.png', sentence: 'THE SUN IS BRIGHT' },
                        { image: 'basketball.png', sentence: 'THE BALL IS ROUND' },
                        { image: 'orange.png', sentence: 'THE ORANGE IS SWEET' }
                    ]
                },
                past_tense: {
                    icon: '🕰️', menuTitle: 'Past Tense', menuDesc: 'Latihan menyusun kalimat lampau.',
                    title: 'Apa Itu Simple Past Tense?',
                    explanation: `<p>Kita menggunakan <strong>Simple Past Tense</strong> untuk membicarakan kegiatan atau kejadian yang <strong>sudah terjadi di masa lampau</strong>.</p><p>Ciri utamanya adalah kata kerja (verb) biasanya ditambahkan akhiran <strong>-ed</strong> (<code>play</code> -> <code>played</code>). Tapi ada juga yang tidak beraturan (<code>eat</code> -> <code>ate</code>).</p>`,
                    examples: [ 'I <strong>played</strong> football yesterday.', 'She <strong>ate</strong> an apple this morning.'],
                    exercises: [
                        { image: 'football.png', sentence: 'HE PLAYED FOOTBALL' },
                        { image: 'banana.png', sentence: 'THE MONKEY ATE A BANANA' },
                        { image: 'bus.png', sentence: 'I RODE THE BUS YESTERDAY' }
                    ]
                },
                future_tense: {
                    icon: '🚀', menuTitle: 'Future Tense', menuDesc: 'Latihan menyusun kalimat akan datang.',
                    title: 'Apa Itu Simple Future Tense?',
                    explanation: `<p>Kita menggunakan <strong>Simple Future Tense</strong> untuk membicarakan kegiatan atau rencana yang <strong>akan terjadi di masa depan</strong>.</p><p>Aturannya adalah menggunakan kata <strong>will</strong> sebelum kata kerja. Polanya: <strong>Subjek + will + Kata Kerja</strong>.</p>`,
                    examples: [ 'I <strong>will go</strong> to the market tomorrow.', 'They <strong>will play</strong> basketball later.'],
                    exercises: [
                        { image: 'bus.png', sentence: 'I WILL RIDE THE BUS' },
                        { image: 'orange.png', sentence: 'HE WILL EAT THE ORANGE' },
                        { image: 'book.png', sentence: 'SHE WILL READ THE BOOK' }
                    ]
                }
            },
            availableExercises: [],
            currentExercise: {},
            shuffledWords: [],
            playerAnswer: [],
            feedback: '',
            isRoundOver: false,
            volume: 0.5,
            isMuted: false,
            musicEl: null,

            init() {
                this.musicEl = document.getElementById('study-music');
                if (this.musicEl) {
                    this.musicEl.src = '{{ asset('audio/music/study music.mp3') }}';
                    this.updateVolume();
                    this.playMusic();
                }
                this.$watch('volume', () => this.updateVolume());
                this.$watch('isMuted', () => this.updateVolume());
            },

            goBack() {
                if (this.currentView !== 'menu') {
                    this.resetToMenu();
                } else {
                    window.location.href = '{{ route('games.exercise-hub') }}';
                }
            },
            resetToMenu() {
                this.currentView = 'menu';
                this.feedback = '';
                this.isRoundOver = false;
            },
            startExercise(topic) {
                this.currentView = topic;
                this.availableExercises = [...this.materialBank[topic].exercises].sort(() => Math.random() - 0.5);
                this.nextSentence();
            },
            nextSentence() {
                if (this.availableExercises.length === 0) {
                    this.feedback = 'Hebat! Kamu sudah menyelesaikan semua latihan di topik ini! 🎉';
                    this.isRoundOver = true;
                    this.currentExercise = {};
                    this.playSound('complete');
                    return;
                }
                this.isRoundOver = false;
                this.feedback = '';
                this.playerAnswer = [];
                this.currentExercise = this.availableExercises.pop();
                let words = this.currentExercise.sentence.split(' ');
                this.shuffledWords = words.sort(() => Math.random() - 0.5).map(word => ({ word: word, used: false }));
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
                if (finalAnswer === this.currentExercise.sentence) {
                    this.feedback = 'Excellent! ✅';
                    this.playSound('correct');
                } else {
                    this.feedback = 'Not quite! The correct sentence is: ' + this.currentExercise.sentence;
                    this.playSound('wrong');
                }
                this.isRoundOver = true;
            },
            playSound(type) {
                if (typeof Tone === 'undefined') return;
                const synth = new Tone.Synth().toDestination();
                if (type === 'correct') { synth.triggerAttackRelease('C5', '8n', Tone.now()); }
                else if (type === 'wrong') { synth.triggerAttackRelease('A2', '8n', Tone.now()); }
                else if (type === 'complete') {
                    const notes = ['C5', 'E5', 'G5', 'C6'];
                    let now = Tone.now();
                    notes.forEach((note, i) => { synth.triggerAttackRelease(note, '8n', now + i * 0.1); });
                }
            },
            updateVolume() {
                if (this.musicEl) {
                    this.musicEl.volume = this.isMuted ? 0 : this.volume;
                }
            },
            toggleMute() {
                this.isMuted = !this.isMuted;
            },
            playMusic() {
                if (this.musicEl) {
                    this.musicEl.play().catch(error => {
                        console.warn('Autoplay musik diblokir. Perlu interaksi pengguna.');
                        document.body.addEventListener('click', () => this.musicEl.play(), { once: true });
                    });
                }
            }
         }">

        <audio id="study-music" loop></audio>

        <div class="fixed bottom-4 left-4 z-30 flex items-center space-x-3 bg-white/70 backdrop-blur-sm p-2 rounded-full shadow-lg">
            <button @click="toggleMute()" class="focus:outline-none">
                <svg x-show="!isMuted && volume > 0" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                <svg x-show="isMuted || volume == 0" x-cloak class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15zM17 14l2-2m0 0l2-2m-2 2l-2 2m2-2l2 2"></path></svg>
            </button>
            <input type="range" min="0" max="1" step="0.01" x-model="volume" class="w-24 h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer">
        </div>

        <header class="w-full max-w-5xl mx-auto mb-8">
            <div class="relative flex items-center justify-center">
                <a href="#" @click.prevent="goBack()" class="absolute left-0 bg-white/50 p-2 rounded-full transform hover:scale-110 transition-transform z-10">
                    <svg class="w-6 h-6 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="w-full text-center text-3xl sm:text-4xl font-bold text-red-800 px-12 capitalize" x-text="currentView === 'menu' ? 'Learn Materials' : materialBank[currentView]?.title || 'Materi'"></h1>
            </div>
        </header>

        <div class="bg-red-50/80 backdrop-blur-sm p-6 sm:p-8 rounded-2xl shadow-lg w-full max-w-5xl mx-auto">
            <div x-show="currentView === 'menu'" x-transition>
                <h2 class="text-2xl font-bold text-gray-700 mb-2 text-center">Pilih Topik</h2>
                <p class="text-gray-600 mb-6 text-center">Klik pada kartu untuk mulai belajar dan berlatih!</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="(material, key) in materialBank" :key="key">
                        <button @click="startExercise(key)" class="bg-white p-4 rounded-xl shadow-lg text-left transform hover:-translate-y-2 transition-transform duration-300">
                            <div class="text-5xl mb-3" x-text="material.icon"></div>
                            <h3 class="text-xl font-bold text-red-700" x-text="material.menuTitle"></h3>
                            <p class="text-sm text-gray-500" x-text="material.menuDesc"></p>
                        </button>
                    </template>
                </div>
            </div>

            <div x-show="currentView !== 'menu'" x-cloak x-transition.opacity.duration.500ms>
                <div class="prose max-w-none text-gray-800">
                    <h2 class="text-red-700 capitalize" x-text="materialBank[currentView]?.title || 'Materi'"></h2>
                    <div x-html="materialBank[currentView]?.explanation || ''"></div>
                    <h4 class="font-bold text-lg text-gray-800 mt-6 mb-2">Contoh dalam Kalimat:</h4>
                    <ul class="list-disc pl-5">
                        <template x-for="example in materialBank[currentView]?.examples || []">
                            <li x-html="example"></li>
                        </template>
                    </ul>
                </div>

                <div class="mt-8 border-t-2 border-red-300 pt-6">
                    <h3 class="font-bold text-xl text-gray-800 mb-2">Ayo Latihan!</h3>
                    <div x-show="!feedback.includes('Hebat!')">
                        <p class="text-gray-700 mb-4">Perhatikan gambar, lalu susun kata-kata di bawah menjadi kalimat yang benar.</p>
                        <div class="bg-gray-100 p-4 rounded-lg not-prose">
                            <div class="bg-white p-4 rounded-lg shadow-inner mb-6 h-48 flex items-center justify-center">
                                <img :src="'/images/cards/' + currentExercise.image" alt="Exercise Image" class="max-w-full max-h-full object-contain" x-show="currentExercise.image">
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-inner mb-6 min-h-[72px] flex justify-center items-center flex-wrap gap-2">
                                <template x-for="(wordObj, index) in playerAnswer" :key="index">
                                    <button @click="removeWord(index)" class="bg-blue-200 text-blue-800 font-bold py-2 px-4 rounded-lg shadow-sm" x-text="wordObj.word"></button>
                                </template>
                            </div>
                            <p class="font-semibold text-gray-600 mb-2">Klik kata-kata di bawah ini sesuai urutan:</p>
                            <div class="flex justify-center items-center flex-wrap gap-3 mb-6">
                                <template x-for="(wordObj, index) in shuffledWords" :key="index">
                                    <button @click="selectWord(wordObj)" :disabled="wordObj.used" :class="{ 'opacity-25 cursor-not-allowed': wordObj.used }" class="bg-white text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transform hover:scale-110 transition" x-text="wordObj.word"></button>
                                </template>
                            </div>
                            <div class="mt-4">
                                <button x-show="!isRoundOver" @click="submitAnswer()" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">Check My Answer</button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <p x-show="feedback" x-text="feedback" class="font-bold my-2 text-lg" :class="{'text-green-600': feedback.includes('Excellent'), 'text-red-500': feedback.includes('Not quite'), 'text-blue-600': feedback.includes('Hebat')}"></p>
                        <button x-show="isRoundOver && !feedback.includes('Hebat!')" @click="nextSentence()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
