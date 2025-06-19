{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-100">
                    <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">Edit Room</h1>

                    <!-- Form for editing a subject -->
                    <form action="{{ route('rooms.update', $room->id) }}" method="POST" class="max-w-2xl mx-auto space-y-6 bg-white p-10 rounded-lg shadow-lg">
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


                        <!-- Subject Name -->
                        <div>
                            <label for="roomname" class="block text-lg font-medium text-gray-700 mb-2">Room Name</label>
                            <input 
                                type="text" 
                                name="roomname" 
                                id="roomname" 
                                class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ $room->roomname }}" 
                                required>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button 
                                type="submit" 
                                class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-150">
                                Update Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
