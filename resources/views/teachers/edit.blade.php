{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Teacher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-100">
                    <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">Edit Teacher</h1>

                    <div class="max-w-2xl mx-auto bg-white p-10 rounded-lg shadow-lg">
                        <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                    
                      <!-- Back Button -->
                      <div class="flex justify-right mb-6">
                        <a href="{{ url()->previous() }}" 
                        class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-150">
                            ← Back
                        </a>
                    </div>

                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter teacher's name" 
                            required
                            value="{{ $teacher->name }}">
                        @error('name')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nickname Input -->
                    <div>
                        <label for="nickname" class="block text-lg font-medium text-gray-700 mb-2">Nickname</label>
                        <input 
                            type="text" 
                            name="nickname" 
                            id="nickname" 
                            class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter teacher's nickname" 
                            required
                            value="{{ $teacher->nickname}}">
                        @error('nickname')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-lg font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter teacher's email" 
                            required
                            value="{{ $teacher->user->email }}">
                        @error('email')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-lg font-medium text-gray-700 mb-2">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter teacher's password" 
                            required>
                        @error('password')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Confirmation Input -->
                    <div>
                        <label for="password_confirmation" class="block text-lg font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Confirm password" 
                            required>
                    </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button 
                                type="submit" 
                                class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-150">
                                Update Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
