{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-visible shadow-sm sm:rounded-lg ">
                <div class="p-6 bg-gray-100">
                    <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center ">Edit Student</h1>

                    <div class="max-w-2xl mx-auto bg-white p-10 rounded-lg shadow-lg">
                        <form action="{{ route('students.update', $student->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Back Button -->
                            <div class="flex justify-right mb-6">
                                <a href="{{ url()->previous() }}" 
                                class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-300">
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
                                    value="{{ $student->name }}" 
                                    required>
                            </div>

                            <!-- English Name Input -->
                            <div>
                                <label for="english_name" class="block text-lg font-medium text-gray-700 mb-2">English Name</label>
                                <input 
                                    type="text" 
                                    name="english_name" 
                                    id="english_name" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ $student->english_name }}" 
                                    required>
                            </div>

                            <!-- Course Input -->
                            <div>
                                <label for="course" class="block text-lg font-medium text-gray-700 mb-2">Course</label>
                                <input 
                                    type="text" 
                                    name="course" 
                                    id="course" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ $student->course }}" 
                                    required>
                            </div>

                            <!-- Level Input -->
                            <div>
                                <label for="level" class="block text-lg font-medium text-gray-700 mb-2">Level</label>
                                <input 
                                    type="text" 
                                    name="level" 
                                    id="level" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ $student->level }}" 
                                    required>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-center">
                                <button 
                                    type="submit" 
                                    class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-300">
                                    Update Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>   --}}