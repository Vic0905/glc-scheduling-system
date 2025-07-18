@php
    $students = App\Models\Student::all();
    $subjects = App\Models\Subject::all();
@endphp

<div class="max-w-10xl mx-auto sm:px-4 lg:px-6">
    <div class="overflow-x-auto"></div>
    <div class="bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg p-6">
        <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl overflow-x-auto overflow-y-auto max-w-full max-h-[700px] text-sm font-sans">

            <table class="min-w-full border-separate border-spacing-0 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 sticky top-0 z-10 shadow">
                    <tr>
                        <th class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-center text-sm">Teacher</th>
                        <th class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-left text-sm">Room</th>
                        @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                            @php
                                $startTime = \Carbon\Carbon::createFromFormat('H:i', $time);
                                $endTime = $startTime->copy()->addMinutes(50);
                            @endphp
                            <th class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-center whitespace-nowrap text-xs">
                                {{ $startTime->format('H:i') }}<br>to<br>{{ $endTime->format('H:i') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @php 
                        $timeSlots = [
                            '08:00' => 'time_8_00_8_50',
                            '09:00' => 'time_9_00_9_50',
                            '10:00' => 'time_10_00_10_50',
                            '11:00' => 'time_11_00_11_50',
                            '12:00' => 'time_12_00_12_50',
                            '13:00' => 'time_13_00_13_50',
                            '14:00' => 'time_14_00_14_50',
                            '15:00' => 'time_15_00_15_50',
                            '16:00' => 'time_16_00_16_50', 
                            '17:00' => 'time_17_00_17_50',
                        ];
                    @endphp

                    @foreach ($rooms as $room)
                        @php
                            $groups = $schedulesByRoom[$room->roomname] ?? collect([]);
                            $groupedByTeacherAndDate = $groups->groupBy(fn ($s) => $s->teacher_id . '_' . $s->schedule_date);
                        @endphp

                        @if ($groupedByTeacherAndDate->isEmpty())
                            <tr>
                                <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm text-gray-500 dark:text-gray-300">
                                    <select name="teacher_id" class="teacher-select block w-full text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" selected class="text-gray-400 dark:text-gray-500">Choose a Teacher</option>
                                        @foreach($teachers->sortBy('name') as $teacher)
                                            <option value="{{ $teacher->user_id }}" data-room-id="{{ $teacher->room_id }}">
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 font-bold text-gray-800 dark:text-gray-200">{{ $room->roomname }}</td>
                                @foreach ($timeSlots as $time => $slotKey)
                                    <td class="px-1 py-2 border border-gray-200 dark:border-gray-600 text-center text-xs text-gray-500 dark:text-gray-300">
                                        <form class="schedule-form" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}" data-slot-key="{{ $slotKey }}">
                                            @csrf
                                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                                            <input type="hidden" name="schedule_time" value="{{ $time }}">
                                            <input type="hidden" name="{{ $slotKey }}" value="1">
                                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                            <input type="hidden" name="teacher_id" value="">

                                            {{-- TEACHER NAME SHOULD BE POPULATE HERE THEN WHEN VIEW TO THE TEACHER INDEX IS BY ROOM NOT TEACHER --}}
                                            <select name="sub_teacher_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                            bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                                                <option value="">Teacher (optional)</option>
                                                @foreach ($teachers->sortBy(fn($t) => $t->user->name) as $teacher)
                                                    <option value="{{ $teacher->user_id }}">{{ $teacher->user->name }}</option>
                                                @endforeach
                                            </select>

                                            <select name="student_id" class="block w-full text-xs py-1 px-2 mb-1 rounded-lg border border-gray-300 dark:border-gray-600 
                                            bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                                                <option value="">Select Student</option>
                                                @foreach($students->sortBy('name') as $student)
                                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                @endforeach
                                            </select>

                                            <select name="subject_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                            bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                                                <option value="">Select Subject</option>
                                                @foreach($subjects->sortBy('subjectname') as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->subjectname }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                @endforeach
                            </tr>
                            @else
                            @foreach ($groupedByTeacherAndDate as $group)
                                <tr class="hover:bg-slate-50 dark:hover:bg-gray-800 align-top transition text-xs">
                                    <td class="px-4 py-2 border-t border-r border-gray-300 dark:border-gray-600 text-center">
                                        <a href="#" onclick="event.preventDefault(); showTeacherStudents({{ $group->first()->teacher->user->id }}, '{{ $group->first()->schedule_date }}')" 
                                        class="text-blue-600 dark:text-blue-400 hover:underline dark:hover:text-blue-600">
                                            {{ $group->first()->teacher->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 font-bold text-gray-800 dark:text-gray-200">{{ $room->roomname }}</td>

                                    @foreach ($timeSlots as $time => $slotKey)
                                        @php
                                            $scheduledStudents = $group->filter(fn($s) => $s->{$slotKey});
                                        @endphp
                                        <td class="px-1 py-2 border border-gray-200 dark:border-gray-600 align-top text-gray-800 dark:text-gray-200">
                                            @if($scheduledStudents->isNotEmpty())
                                                @foreach($scheduledStudents as $schedule)
                                                    <div class="bg-white dark:bg-gray-900 border dark:border-gray-900 rounded-md p-1 mb-1">
                                                        <div class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ $schedule->student->name ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-600 dark:text-gray-300">{{ optional($schedule->subject)->subjectname ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-600 dark:text-gray-400">
                                                        @if($schedule->subTeacher && $schedule->subTeacher->name)
                                                            <span class="text-xs text-gray-600 dark:text-gray-400">Teacher: {{ $schedule->subTeacher->name }}</span>
                                                        @else
                                                            <span class="text-gray-500 italic">No Substitute</span>
                                                        @endif
                                                        {{-- to VIEW THE TEACHER ROOM INSTEAD OF THE TEACHER NAME  --}}
                                                            {{-- @if($schedule->subTeacher && $schedule->subTeacher->room)
                                                            <span>{{ $schedule->subTeacher->room->roomname }}</span>
                                                        @else
                                                            <span class="text-gray-500 italic">No Room</span>
                                                        @endif --}} 

                                                        <div class="flex space-x-2 mt-1">
                                                            <button onclick="clearSchedule({{ $schedule->id }}, event)" class="text-blue-600 text-xs hover:underline">Clear</button>
                                                            <button onclick="deleteSchedule({{ $schedule->id }})" class="text-red-500 text-xs hover:underline">Delete</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <form class="schedule-form" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}" data-slot-key="{{ $slotKey }}">
                                                    @csrf
                                                    <input type="hidden" name="teacher_id" value="{{ $group->first()->teacher_id }}">
                                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                    <input type="hidden" name="schedule_time" value="{{ $time }}">
                                                    <input type="hidden" name="{{ $slotKey }}" value="1">
                                                    <input type="hidden" name="schedule_date" value="{{ $group->first()->schedule_date }}">

                                                    <select name="sub_teacher_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                                    bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                                                        <option value="">Teacher (optional)</option>
                                                        @foreach ($teachers->sortBy(fn($t) => $t->user->name) as $teacher)
                                                            <option value="{{ $teacher->user_id }}">{{ $teacher->user->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <select name="student_id" class="block w-full text-xs py-1 px-2 mb-1 rounded-lg border border-gray-300 dark:border-gray-600 
                                                    bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                                                        <option value="">Select Student</option>
                                                        @foreach($students->sortBy('name') as $student)
                                                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <select name="subject_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                                    bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                                                        <option value="">Select Subject</option>
                                                        @foreach($subjects->sortBy('subjectname') as $subject)
                                                            <option value="{{ $subject->id }}">{{ $subject->subjectname }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-end text-xs mt-2 dark:text-gray-300">
        {{ $rooms->links() }}
    </div>
</div>  



<div id="teacherStudentsModalContainer"></div>

<script>
    function showTeacherStudents(teacherId, scheduleDate) {
        fetch(`/teachers/${teacherId}/students/${scheduleDate}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('teacherStudentsModalContainer').innerHTML = html;
                document.getElementById('teacherStudentsModal').classList.remove('hidden');
                attachStatusChangeListeners();
            })
            .catch(error => {
                console.error('Error loading students:', error);
                alert('Error loading students. Please check the console.');
            });
    }

    function closeTeacherStudentsModal() {
        document.getElementById('teacherStudentsModal').classList.add('hidden');
    }

    function attachStatusChangeListeners() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function () {
                let studentId = this.dataset.studentId;
                let newStatus = this.value;
                updateStudentStatus(studentId, newStatus);
            });
        });
    }
</script>