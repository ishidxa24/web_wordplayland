{{-- Hanya tampilkan komponen ini jika ada pesan notifikasi di session --}}
@if (session('new_title_notification'))
    <div
        {{-- Logika Alpine.js untuk menampilkan & menyembunyikan notifikasi --}}
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-10"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-10"
        x-cloak
        class="fixed top-5 right-5 z-50 bg-white border-l-4 border-green-400 text-gray-800 p-4 rounded-lg shadow-lg flex items-center max-w-sm"
        role="alert">

        <div class="text-2xl mr-4">🎉</div>
        <div>
            <p class="font-bold">New Title Unlocked!</p>
            {{-- Menggunakan {!! !!} agar tag <strong> dari Controller bisa tampil --}}
            <p class="text-sm">{!! session('new_title_notification') !!}</p>
        </div>
        <button @click="show = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
    </div>
@endif
