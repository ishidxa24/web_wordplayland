<x-app-layout>
    <x-slot name="header">
        {{-- Sekarang header hanya berisi judul, lebih bersih --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ▼▼▼ TOMBOL KEMBALI SEKARANG DIPINDAHKAN KE SINI ▼▼▼ --}}
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm border border-transparent rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150 shadow">
                    &larr; Back to Dashboard
                </a>
            </div>

            <div class="space-y-6">
                {{-- KARTU INFORMASI PROFIL --}}
                <div class="p-4 sm:p-8 bg-white/70 backdrop-blur-sm shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Profile Information') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __("Update your account's profile information and email address.") }}
                                </p>
                            </header>

                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('patch')

                                <div>
                                    <x-input-label for="name" :value="__('Name')" class="text-gray-700" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-white" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-white" :value="old('email', $user->email)" required autocomplete="username" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div>
                                            <p class="text-sm mt-2 text-gray-800">
                                                {{ __('Your email address is unverified.') }}
                                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>
                                            </p>
                                            @if (session('status') === 'verification-link-sent')
                                                <p class="mt-2 font-medium text-sm text-green-600">
                                                    {{ __('A new verification link has been sent to your email address.') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button class="bg-blue-500 hover:bg-blue-600">{{ __('Save') }}</x-primary-button>
                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                </div>

                {{-- KARTU UPDATE PASSWORD --}}
                <div class="p-4 sm:p-8 bg-white/70 backdrop-blur-sm shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Update Password') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                </p>
                            </header>
                            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')
                                <div>
                                    <x-input-label for="current_password" :value="__('Current Password')" class="text-gray-700" />
                                    <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full bg-white" autocomplete="current-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="password" :value="__('New Password')" class="text-gray-700" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full bg-white" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-white" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-primary-button class="bg-blue-500 hover:bg-blue-600">{{ __('Save') }}</x-primary-button>
                                    @if (session('status') === 'password-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                </div>

                {{-- KARTU HAPUS AKUN --}}
                <div class="p-4 sm:p-8 bg-white/70 backdrop-blur-sm shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
