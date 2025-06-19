<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Subject') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                 <!-- Success Message -->
                    @if(session('success'))
                        <div id="successMessage"
                            class="fixed top-12 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                            {{ session('success') }}
                        </div>
                    @endif

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const successMessage = document.getElementById('successMessage');
                            if (successMessage) {
                                setTimeout(() => {
                                    successMessage.style.transition = 'opacity 1s ease';
                                    successMessage.style.opacity = '0';
                                    setTimeout(() => successMessage.remove(), 500);
                                }, 3000);
                            }
                        });
                    </script>

                <div class="p-6 bg-gray-100">

                    <div class="max-w-md mx-auto bg-gray-100 p-10 rounded-lg shadow-lg">
                        <form action="{{ route('subjects.store') }}" method="POST" class="space-y-6">
                            @csrf
                            
                        <!-- Back Button -->
                        <div class="flex justify-right mb-6">
                            <a href="{{ url()->previous() }}" 
                            class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-300">
                                ← Back
                            </a>
                        </div>

                            <!-- Subject Name Input -->
                            <div>
                                <label for="subjectname" class="block text-lg font-medium text-gray-700 mb-2">Subject Name</label>
                                <input 
                                    type="text" 
                                    name="subjectname" 
                                    id="subjectname" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter subject name" 
                                    required>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-center">
                                <button 
                                    type="submit" 
                                    class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-300">
                                    Add Subject
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
