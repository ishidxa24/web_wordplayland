{{-- File: resources/views/layouts/navigation.blade.php --}}
{{-- Versi ini menggunakan modal custom untuk konfirmasi logout --}}

<nav x-data="{ open: false, showLogoutModal: false }" class="sticky top-0 p-4 z-20 bg-sky-200/80 backdrop-blur-sm w-full">

    <div class="container mx-auto flex justify-end items-center">
        {{-- Tampilan Desktop --}}
        <div class="hidden sm:flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="nav-button">Dashboard</a>
            <a href="{{ route('scoreboard') }}" class="nav-button">Scoreboard</a>

            {{-- ▼▼▼ PERUBAIKAN: Tombol ini sekarang memunculkan modal ▼▼▼ --}}
            <button @click.prevent="showLogoutModal = true" type="button" class="nav-button bg-red-500/80 hover:bg-red-600/80 text-white">
                Log Out
            </button>
        </div>

        {{-- Tampilan Mobile --}}
        <div class="sm:hidden">
            <button @click="open = !open" class="nav-button p-2">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="open" style="display: none;" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Dropdown Mobile --}}
    <div x-show="open" @click.away="open = false" class="sm:hidden mt-2 absolute top-16 right-4 w-48" x-cloak>
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
            <a href="{{ route('scoreboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Scoreboard</a>
            <div class="border-t border-gray-100 my-1"></div>
            {{-- ▼▼▼ PERUBAIKAN: Link ini sekarang memunculkan modal ▼▼▼ --}}
            <a href="#" @click.prevent="showLogoutModal = true; open = false" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                Log Out
            </a>
        </div>
    </div>

    {{-- ▼▼▼ KODE BARU: Modal Konfirmasi Logout ▼▼▼ --}}
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
</nav>
