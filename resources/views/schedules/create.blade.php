<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
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
                            }, 5000);
                        }
                    });
                </script>

                <!-- Create Schedule Form -->
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf    

                    @if ($errors->any())
                        <div id="error-message" class="text-red-500 text-m mt-2">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <script>
                            setTimeout(function() {
                                document.getElementById('error-message').style.display = 'none';
                            }, 90000); // Hide after 3 seconds
                        </script>
                    @endif

                      <!-- Back Button -->
                      <div class="flex justify-right mb-6 mt-2">
                        <a href="{{ url()->previous() }}" 
                        class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                              border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-150">
                            ← Back
                        </a>
                    </div>

                    <div class="mb-6">
                        <label for="student_id" class="block text-lg font-medium text-gray-700 mb-2">Student</label>
                        <select 
                            name="student_id" 
                            id="student_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required
                            @role('teacher') disabled @endrole>
                            @foreach($students->sortBy('name') as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="student_room_id" class="block text-lg font-medium text-gray-700 mb-2">Student Room</label>
                        <select 
                            name="student_room_id" 
                            id="student_room_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->roomname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="schedule_time" class="block text-lg font-medium text-gray-700 mb-2">Schedule Time</label>
                        <select 
                            name="schedule_time" 
                            id="schedule_time" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                            @role('teacher') disabled @endrole>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="12:00">12:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                        </select>
                    </div>
                    

                    <div class="mb-6">
                        <label for="teacher_id" class="block text-lg font-medium text-gray-700 mb-2">Teacher</label>
                        <select 
                            name="teacher_id" 
                            id="teacher_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required
                            @role('teacher') disabled @endrole>
                            @foreach($teachers->sortBy('name') as $teacher)
                                <option value="{{ $teacher->user_id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-6">
                        <label for="room_id" class="block text-lg font-medium text-gray-700 mb-2"> Teacher Room</label>
                        <select 
                            name="room_id" 
                            id="room_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required
                            @role('teacher') disabled @endrole>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->roomname }}</option>
                            @endforeach
                        </select>
                    </div>

                        
                    <div class="mb-6">
                        <label for="subject_id" class="block text-lg font-medium text-gray-700 mb-2">Subject</label>
                        <select 
                            name="subject_id" 
                            id="subject_id" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required
                            @role('teacher') disabled @endrole>
                            @foreach($subjects->sortBy('subjectname') as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subjectname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="start_date" class="block text-lg font-medium text-gray-700 mb-2">Start Date</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            id="start_date" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="end_date" class="block text-lg font-medium text-gray-700 mb-2">End Date</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="end_date" 
                            class="block w-full text-md py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                    </div>
                    
                    <div class="flex justify-center">
                        <input type="hidden" name="status" value="N/A">
                        <button 
                            type="submit" 
                            class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                 border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-150">
                            Add Schedule
                        </button>
                    </div>          
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{--     
    <script>
        $(document).ready(function () {
            // Check if there is an error for the schedule time | this is to hide the error message after 5 seconds 
                // Hide the error message after 5 seconds if it exists 
                setTimeout(function () {
                    $('#schedule-time-error').fadeOut(500, function() {
                        $(this).remove(); // Remove the element completely 
                    });
                }, 5000);
            }
    
            // Check if there is an error for the room | this is to hide the error message after 5 seconds
            if ($('#room-error').length > 0) {
                // Hide the room error after 5 seconds if it exists 
                setTimeout(function () {
                    $('#room-error').fadeOut(500, function() {
                        $(this).remove(); // Remove the element completely 
                    });
                }, 5000);
            }
        );
    </script> --}}
    
</x-app-layout>
