
@php
    $students = App\Models\Student::all();
    $subjects = App\Models\Subject::all();
@endphp
<div class="py-2">
    <div class="max-w-10xl mx-auto sm:px-4 lg:px-6 ">
        <div class="overflow-x-auto"></div>
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
 
            <div class="bg-white shadow-xl rounded-2xl overflow-x-auto overflow-y-auto max-w-full max-h-[700px] text-sm font-sans">
                <div class="overflow-x-auto"></div>
                <table class="min-w-full border-separate border-spacing-0 text-sm">
                    <thead class="bg-gray-100 text-gray-900 sticky top-0 z-10 shadow">
                        <tr>
                            <th class="px-4 py-3 border border-gray-200 text-center text-sm">Teacher</th>
                            <th class="px-4 py-3 border border-gray-200 text-left text-sm">Room</th>
                            @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                                @php
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $time);
                                    $endTime = $startTime->copy()->addMinutes(50);
                                @endphp
                                <th class="px-4 py-3 border border-gray-200 text-center whitespace-nowrap text-xs">
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
                                $groupedByTeacherAndDate = $groups->groupBy(function ($schedule) {
                                    return $schedule->teacher_id . '_' . $schedule->schedule_date;
                                });
                            @endphp

                            @if ($groupedByTeacherAndDate->isEmpty())
                                <tr>
                                    <td class="px-4 py-3 border text-sm text-gray-500"> 
                                        <select name="teacher_id" class="teacher-select block w-full appearance-none text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-xl p-3 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" data-room-id="{{ $room->id }}">
                                            <option value="" selected class="text-gray-400">Choose a Teacher</option>
                                            @foreach($teachers->sortBy('name') as $teacher)
                                                <option value="{{ $teacher->user_id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 border font-bold">{{ $room->roomname }}</td>
                                    @foreach ($timeSlots as $time => $slotKey)
                                        <td class="px-1 py-2 border text-center text-xs text-gray-500">
                                            <form class="schedule-form" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}" data-slot-key="{{ $slotKey }}">
                                                @csrf
                                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                <input type="hidden" name="schedule_time" value="{{ $time }}">
                                                <input type="hidden" name="{{ $slotKey }}" value="1">
                                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">

                                                
                                                {{-- THIS IS THE CRUCIAL ADDITION for empty rows --}}
                                                <input type="hidden" name="teacher_id" value=""> 
                                                
                                                <select name="student_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-1" required>
                                                    <option value="" selected>Select Student</option>
                                                    @foreach($students->sortBy('name') as $student)
                                                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                    @endforeach
                                                </select>

                                                <select name="subject_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                    <option value="" selected>Select Subject</option>
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
                                    <tr class="hover:bg-slate-50 align-top transition text-xs">
                                        <td class="px-4 py-3 border font-medium text-sm text-center">
                                                {{ $group->first()->teacher->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 border font-bold">{{ $room->roomname }}</td>

                                        @foreach ($timeSlots as $time => $slotKey)
                                            @php
                                                $scheduledStudents = $group->filter(fn($schedule) => $schedule->{$slotKey});
                                            @endphp
                                            <td class="px-1 py-2 border align-top"> 
                                                @if($scheduledStudents->isNotEmpty())
                                                    @foreach($scheduledStudents as $schedule)
                                                    <div class="schedule-item bg-gray-100 border rounded-md ">
                                                        <div class="text-xs text-gray-600">{{ $schedule->student->name ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-600">{{ optional($schedule->subject)->subjectname ?? 'N/A' }}</div>
                                                        <button onclick="clearSchedule({{ $schedule->id }}, event)" class="text-blue-600 text-xs hover:underline">
                                                            Clear
                                                        </button>

                                                        <button onclick="deleteSchedule({{ $schedule->id }})" class="text-red-500 text-xs hover:underline">Delete</button>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <form class="schedule-form" data-room-id="{{ $room->id }}" data-time-slot="{{ $time }}" data-slot-key="{{ $slotKey }}">
                                                        @csrf
                                                        {{-- This hidden input is already present here --}}
                                                        <input type="hidden" name="teacher_id" value="{{ $group->first()->teacher_id }}">
                                                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                        <input type="hidden" name="schedule_time" value="{{ $time }}">
                                                        <input type="hidden" name="{{ $slotKey }}" value="1">
                                                        <input type="hidden" name="schedule_date" value="{{ $group->first()->schedule_date }}">
                                                        
                                                        <select name="student_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-1" required>
                                                            <option value="" selected>Select Student</option>
                                                            @foreach($students->sortBy('name') as $student)
                                                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        <select name="subject_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                            <option value="" selected>Select Subject</option>
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
            <div class="flex justify-end text-xs mt-2">
                    {{ $rooms->links() }}
                </div>
        </div>
    </div>
</div>
<div id="teacherStudentsModalContainer"></div>