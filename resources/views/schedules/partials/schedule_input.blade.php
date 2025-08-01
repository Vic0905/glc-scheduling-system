@php
    $students = App\Models\Student::all();
    $subjects = App\Models\Subject::all();
@endphp

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.show-form-btn').forEach(button => {
            button.addEventListener('click', function () {
                const cell = button.closest('.schedule-cell');
                const form = cell.querySelector('.hidden');
                button.classList.add('hidden');
                form.classList.remove('hidden');
            });
        });
    });
</script>

<div class="max-w-10xl mx-auto sm:px-4 lg:px-6">
    <div class="overflow-x-auto"></div>
    <div class="bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg p-6">
        <div class="bg-white dark:bg-gray-900 rounded-2xl overflow-x-auto overflow-y-auto max-w-full max-h-[700px] text-sm font-sans">

            <table class="min-w-full border-separate border-spacing-0 text-xs sm:text-xs md:text-base lg:text-xs text-gray-900 dark:text-white">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 sticky top-0 z-10 shadow">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">Teacher</th>
                        <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">Room</th>
                        @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'] as $time)
                            @php
                                $startTime = \Carbon\Carbon::createFromFormat('H:i', $time);
                                $endTime = $startTime->copy()->addMinutes(50);
                            @endphp
                            <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">
                                {{ $startTime->format('H:i') }} – {{ $endTime->format('H:i') }}
                            </th>

                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        @php
                            $groups = $schedulesByRoom[$room->roomname] ?? collect([]);
                            $groupedByTeacherAndDate = $groups->groupBy(fn ($s) => $s->teacher_id . '_' . $s->schedule_date);
                        @endphp

                        @if ($groupedByTeacherAndDate->isEmpty())
                            <tr>
                                <td class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-300">
                                    <select name="teacher_id" class="teacher-select block w-full text-xs font-medium text-gray-700 dark:text-gray-200 bg-white 
                                    dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl p-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" selected class="text-gray-400 dark:text-gray-500 text-center">Choose a Teacher</option>
                                        @foreach($teachers->sortBy('name') as $teacher)
                                            <option value="{{ $teacher->user_id }}" data-room-id="{{ $teacher->room_id }}">
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 border border-gray-200 dark:border-gray-700 font-bold text-gray-800 dark:text-gray-200 text-center">{{ $room->roomname }}</td>
                                @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'] as $time)
                                    @php
                                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $time)->addMinutes(50)->format('H:i');
                                        $timeSlotValue = "{$time}-{$endTime}";
                                    @endphp
                                    <td class="px-1 py-2 border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-300 text-center">
                                        <div class="schedule-cell" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}">
                                            <button type="button" class="show-form-btn text-blue-600 hover:underline text-xs">Create Record</button>
                                            <div class="hidden mt-1">
                                                <form class="schedule-form" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}">
                                                    @csrf
                                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                    <input type="hidden" name="time_slot" value="{{ $timeSlotValue }}">
                                                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                    <input type="hidden" name="teacher_id" value="">

                                                    {{-- Selects --}}
                                                    <select name="sub_teacher_id" class="block w-full text-xs py-1 px-1 rounded-lg border border-gray-300
                                                     dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                                                        <option value="">Select Teacher</option>
                                                        @foreach ($teachers->sortBy(fn($t) => $t->user->name) as $teacher)
                                                            <option value="{{ $teacher->user_id }}">{{ $teacher->user->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <select name="student_id" class="block w-full text-xs py-1 px-1 mb-1 rounded-lg border border-gray-300
                                                     dark:border-gray-700 bg-white dark:bg-gray-900">
                                                        <option value="">Select Student</option>
                                                        @foreach($students->sortBy('name') as $student)
                                                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <select name="subject_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300
                                                     dark:border-gray-700 bg-white dark:bg-gray-900">
                                                        <option value="">Select Subject</option>
                                                        @foreach($subjects->sortBy('subjectname') as $subject)
                                                            <option value="{{ $subject->id }}">{{ $subject->subjectname }}</option>
                                                        @endforeach
                                                    </select>

                                                    <label class="inline-flex items-start mt-2">
                                                        <input type="checkbox" name="repeat_week" value="1" class="form-checkbox h-3 w-3 text-blue-600">
                                                        <span class="ml-1 text-xs text-gray-700 dark:text-gray-300">Repeat until Friday</span>
                                                    </label>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @else
                            @foreach ($groupedByTeacherAndDate as $group)
                                <tr class="hover:bg-slate-50 dark:hover:bg-gray-900 align-middle text-center text-xs">
                                    <td class="px-4 py-2 border-t border-r border-l border-gray-300 dark:border-gray-700 text-center">
                                        <a href="#" onclick="event.preventDefault(); showTeacherStudents({{ $group->first()->teacher->user->id }}, '{{ $group->first()->schedule_date }}')" 
                                           class="text-blue-600 dark:text-blue-400 hover:underline dark:hover:text-blue-600">
                                            {{ $group->first()->teacher->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 border border-gray-200 dark:border-gray-700 font-bold text-gray-800 dark:text-gray-200 text-center">{{ $room->roomname }}</td>

                                    @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'] as $time)
                                        @php
                                            $endTime = \Carbon\Carbon::createFromFormat('H:i', $time)->addMinutes(50)->format('H:i');
                                            $timeSlotValue = "{$time}-{$endTime}";
                                            $scheduledStudents = $group->where('time_slot', $timeSlotValue);
                                        @endphp
                                        <td class="px-1 py-2 border border-gray-200 dark:border-gray-700 align-middle text-gray-800 dark:text-gray-200">
                                            @if($scheduledStudents->isNotEmpty())
                                                @foreach($scheduledStudents as $schedule)
                                                    <div class="bg-green-200 dark:bg-indigo-900 border dark:border-gray-900 rounded-md p-1 mb-1">
                                                        <div class="text-xs font-medium">{{ $schedule->student->name ?? 'N/A' }}</div>
                                                        <div class="text-xs">{{ optional($schedule->subject)->subjectname ?? 'N/A' }}</div>
                                                        <div class="text-xs">
                                                            @if($schedule->subTeacher && $schedule->subTeacher->name)
                                                                <span class="text-xs">Teacher: {{ $schedule->subTeacher->name }}</span>
                                                            @else
                                                                <span class="text-gray-500 italic">No Substitute</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex justify-center">
                                                        <button onclick="deleteSchedule({{ $schedule->id }})" class="text-red-500 text-xs hover:underline">Delete</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="schedule-cell" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}">
                                                    <button type="button" class="show-form-btn text-blue-600 hover:underline text-xs text-center">Create Record</button>
                                                    <div class="hidden mt-1">
                                                        <form class="schedule-form" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}">
                                                            @csrf
                                                            <input type="hidden" name="teacher_id" value="{{ $group->first()->teacher_id }}">
                                                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                            <input type="hidden" name="time_slot" value="{{ $timeSlotValue }}">
                                                            <input type="hidden" name="schedule_date" value="{{ $group->first()->schedule_date }}">

                                                            {{-- Selects --}}
                                                            <select name="sub_teacher_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300
                                                             dark:border-gray-700 bg-white dark:bg-gray-900">
                                                                <option value="">Select Teacher</option>
                                                                @foreach ($teachers->sortBy(fn($t) => $t->user->name) as $teacher)
                                                                    <option value="{{ $teacher->user_id }}">{{ $teacher->user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <select name="student_id" class="block w-full text-xs py-1 px-2 mb-1 rounded-lg border border-gray-300
                                                             dark:border-gray-700 bg-white dark:bg-gray-900">
                                                                <option value="">Select Student</option>
                                                                @foreach($students->sortBy('name') as $student)
                                                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <select name="subject_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300
                                                             dark:border-gray-700 bg-white dark:bg-gray-900">
                                                                <option value="">Select Subject</option>
                                                                @foreach($subjects->sortBy('subjectname') as $subject)
                                                                    <option value="{{ $subject->id }}">{{ $subject->subjectname }}</option>
                                                                @endforeach
                                                            </select>

                                                            <label class="inline-flex items-start mt-2">
                                                                <input type="checkbox" name="repeat_week" value="1" class="form-checkbox h-3 w-3 text-blue-600">
                                                                <span class="ml-1 text-xs text-gray-700 dark:text-gray-300">Repeat until Friday</span>
                                                            </label>
                                                        </form>
                                                    </div>
                                                </div>
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
        <div>
            {{ $rooms->withQueryString()->links() }}
        </div>
    </div>
</div>


