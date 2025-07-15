<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 bg-white shadow-sm sm:rounded-lg p-6">
                <!-- Success Message -->
                @if(session('success'))
                    <div id="successMessage" class="bg-green-600 text-white p-4 mb-6 rounded-lg shadow-md">
                        {{ session('success') }}
                    </div>
                @endif

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const successMessage = document.getElementById('successMessage');
                        if (successMessage) {
                            setTimeout(() => {
                                successMessage.style.transition = 'opacity 1.0s ease';
                                successMessage.style.opacity = '0';
                                setTimeout(() => successMessage.remove(), 500);
                            }, 3000);
                        }
                    });
                </script>
                
                    <!-- Back Button -->
                    <div class="flex justify-right mb-6">
                        <a href="{{ url()->previous() }}" 
                           class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-150">
                            ← Back
                        </a>
                    </div>

                <!-- Edit Schedule Form -->
                <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Student Selection -->
                    <div class="mb-6">
                        <label for="student_id" class="block text-lg font-medium dark:text-gray-100 text-gray-700 mb-2">Student</label>
                        <select 
                            name="student_id" 
                            id="student_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border dark:bg-gray-900 dark:border-gray-700 border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                            @foreach($students->sortBy('name') as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', $schedule->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->name ?? 'Unnamed Student' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Student Room Selection -->
                    <div class="mb-6">
                        <label for="student_room_id" class="block text-lg font-medium dark:text-gray-100 text-gray-700 mb-2">Student Room</label>
                        <select 
                            name="student_room_id" 
                            id="student_room_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border dark:bg-gray-900 dark:border-gray-700 border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('student_room_id', $schedule->student_room_id) == $room->id ? 'selected' : '' }}>
                                    {{ $room->roomname ?? 'Unnamed Room' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Teacher Selection -->
                    <div class="mb-6">
                        <label for="teacher_id" class="block text-lg font-medium dark:text-gray-100 text-gray-700 mb-2">Teacher</label>
                        <select 
                            name="teacher_id" 
                            id="teacher_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border dark:bg-gray-900 dark:border-gray-700 border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                            @foreach($teachers->sortBy('name') as $teacher)
                                <option value="{{ $teacher->user_id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->user_id ? 'selected' : '' }}>
                                    {{ $teacher->name ?? 'Unnamed Teacher' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subject Selection -->
                    <div class="mb-6">
                        <label for="subject_id" class="block text-lg font-medium dark:text-gray-100 text-gray-700 mb-2">Subject</label>
                        <select 
                            name="subject_id" 
                            id="subject_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border dark:bg-gray-900 dark:border-gray-700 border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                            @foreach($subjects->sortBy('subjectname') as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->subjectname ?? 'Unnamed Subject' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Teacher Room Selection -->
                    <div class="mb-6">
                        <label for="room_id" class="block text-lg font-medium dark:text-gray-100 text-gray-700 mb-2">Teacher Room</label>
                        <select 
                            name="room_id" 
                            id="room_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border dark:bg-gray-900 dark:border-gray-700 border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id', $schedule->room_id) == $room->id ? 'selected' : '' }}>
                                    {{ $room->roomname ?? 'Unnamed Room' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-lg font-medium dark:text-gray-100 text-gray-700 mb-2">Status</label>
                        <select 
                            name="status" 
                            id="status" 
                            class="block w-full text-md py-3 px-4 rounded-lg border dark:bg-gray-900 dark:border-gray-700 border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                            @foreach(['N/A', 'present MTM', 'present GRP', 'absent MTM', 'absent GRP'] as $status)
                                <option value="{{ $status }}" {{ old('status', $schedule->status) == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center">
                        <button 
                            type="submit" 
                            class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-150">
                            Update Schedule
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
