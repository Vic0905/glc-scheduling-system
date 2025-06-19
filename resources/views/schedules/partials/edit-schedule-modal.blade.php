<!-- Edit Schedule Modal -->
<div id="editScheduleModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-2xl mx-4 transition-all">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Schedule</h2>
            <button onclick="closeEditScheduleModal()" class="text-gray-500 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="editScheduleForm" method="POST" action="{{ route('schedules.update', $schedule->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Student -->
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                <select name="student_id" id="student_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    @foreach($students->sortBy('name') as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $schedule->student_id) == $student->id ? 'selected' : '' }}>
                            {{ $student->name ?? 'Unnamed Student' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Student Room -->
            <div>
                <label for="student_room_id" class="block text-sm font-medium text-gray-700 mb-1">Student Room</label>
                <select name="student_room_id" id="student_room_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('student_room_id', $schedule->student_room_id) == $room->id ? 'selected' : '' }}>
                            {{ $room->roomname ?? 'Unnamed Room' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Teacher -->
            <div>
                <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                <select name="teacher_id" id="teacher_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    @foreach($teachers->sortBy('name') as $teacher)
                        <option value="{{ $teacher->user_id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->user_id ? 'selected' : '' }}>
                            {{ $teacher->name ?? 'Unnamed Teacher' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject -->
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <select name="subject_id" id="subject_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    @foreach($subjects->sortBy('subjectname') as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->subjectname ?? 'Unnamed Subject' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Teacher Room -->
            <div>
                <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Teacher Room</label>
                <select name="room_id" id="room_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id', $schedule->room_id) == $room->id ? 'selected' : '' }}>
                            {{ $room->roomname ?? 'Unnamed Room' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    @foreach(['N/A', 'present MTM', 'present GRP', 'absent MTM', 'absent GRP'] as $status)
                        <option value="{{ $status }}" {{ old('status', $schedule->status) == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeEditScheduleModal()"
                    class="bg-gray-900 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                           border-2 border-gray-200 hover:border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition ease-in duration-100">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                           border-2 border-blue-500 hover:border-gray-100 text-gray-100 hover:text-blue-900 rounded-lg transition ease-in duration-100">
                    Update Schedule
                </button>
            </div>
        </form>
    </div>
</div>
