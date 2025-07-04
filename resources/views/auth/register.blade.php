<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white" />
            <x-text-input
                id="name"
                class="block mt-1 w-full bg-white bg-opacity-90 text-black border border-gray-300 rounded-md focus:ring focus:ring-indigo-300 focus:outline-none"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white" />
            <x-text-input
                id="email"
                class="block mt-1 w-full bg-white bg-opacity-90 text-black border border-gray-300 rounded-md focus:ring focus:ring-indigo-300 focus:outline-none"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-white" />
            <x-text-input
                id="password"
                class="block mt-1 w-full bg-white bg-opacity-90 text-black border border-gray-300 rounded-md focus:ring focus:ring-indigo-300 focus:outline-none"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full bg-white bg-opacity-90 text-black border border-gray-300 rounded-md focus:ring focus:ring-indigo-300 focus:outline-none"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="underline text-sm text-white hover:text-gray-300">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ml-3 bg-gray-800 hover:bg-gray-700 text-white">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
