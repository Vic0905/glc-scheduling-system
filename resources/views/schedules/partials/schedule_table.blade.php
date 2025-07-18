<div class="py-2">
    <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 sm:rounded-none p-0 shadow-none border border-gray-300 dark:border-gray-700">
            <div class="overflow-auto max-w-full max-h-[700px] text-sm font-sans border-t border-l border-gray-300 dark:border-gray-700">
                <table class="min-w-full border-separate border-spacing-0 text-xs sm:text-xs md:text-base lg:text-xs text-gray-900 dark:text-white">
                    <thead class="sticky top-0 z-10 bg-gray-100 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">Teacher</th>
                            <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">Room</th>
                            <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">Schedule Date</th>
                            @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                                @php
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $time);
                                    $endTime = $startTime->copy()->addMinutes(50);
                                @endphp
                                <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2p">
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

                        @foreach($groupedSchedules as $group)
                            <tr class="hover:bg-green-50 dark:hover:bg-gray-900 transition text-center text-xs align-top">
                                <td class="px-4 py-2 border-t border-r border-gray-300 dark:border-gray-700 w-40 max-w-[160px]">
                                    <a href="#"
                                    onclick="event.preventDefault(); showTeacherStudents({{ $group->first()->teacher->user->id }}, '{{ $group->first()->schedule_date }}')" 
                                    class="text-blue-600 dark:text-blue-400 hover:underline dark:hover:text-blue-600 break-words">
                                        {{ $group->first()->teacher->name ?? 'N/A' }}
                                    </a>
                                </td>

                                <td class="px-4 py-2 border-t border-r border-gray-300 dark:border-gray-700 font-bold w-32 max-w-[120px] break-words">
                                    {{ $group->first()->room->roomname ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-2 border-t border-r border-gray-300 dark:border-gray-700 font-bold w-36 max-w-[140px] break-words">
                                    {{ $group->first()->schedule_date ?? 'N/A' }}
                                    @if ($group->first()->schedule_date)
                                        <br>
                                        <span class="text-red-500">({{ \Carbon\Carbon::parse($group->first()->schedule_date)->format('l') }})</span>
                                    @endif
                                </td>

                                @foreach($timeSlots as $time => $slotKey)
                                    @php
                                        $scheduledStudents = $group->filter(fn($s) => $s->{$slotKey});
                                    @endphp
                                    <td class="px-2 py-2 border-t border-r border-gray-300 dark:border-gray-700 align-top w-56 max-w-[220px]">
                                        @if($scheduledStudents->isNotEmpty())
                                            @foreach($scheduledStudents as $schedule)
                                                @php
                                                    $status = $schedule->status ?? 'N/A';
                                                    $isAbsent = in_array($status, ['N/A', 'absent GRP', 'absent MTM']);
                                                    $textColor = $isAbsent ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300';
                                                    $bgColor = $isAbsent ? 'bg-red-50 dark:bg-gray-900' : 'bg-green-50 dark:bg-gray-900';
                                                @endphp
                                                <div class="{{ $bgColor }} mb-1 p-1 text-[10px] sm:text-xs rounded leading-tight">
                                                    <div class="flex flex-col space-y-1 text-[11px] sm:text-xs">
                                                        <!-- Student name -->
                                                        <span class="font-medium whitespace-nowrap">
                                                            {{ $schedule->student->name ?? 'N/A' }}
                                                        </span>

                                                        <!-- Subject -->
                                                        <span class="whitespace-nowrap">
                                                            {{ $schedule->subject->subjectname ?? 'N/A' }}
                                                        </span>

                                                        <!-- Room -->
                                                        <strong class="whitespace-nowrap">
                                                            {{ $schedule->subTeacher->room->roomname ?? $schedule->teacher->room->roomname ?? 'No Room' }}
                                                        </strong>
                                                    </div>

                                                        <div>
                                                        @role('teacher')
                                                            @php
                                                                $subRoomId = $schedule->subTeacher->room->id ?? null;
                                                                $mainRoomId = $schedule->teacher->room->id ?? null;
                                                            @endphp

                                                            @if ($subRoomId === $mainRoomId)
                                                                <form action="{{ route('schedules.updateStatus', ['id' => $schedule->id]) }}" method="POST" class="flex flex-col gap-2 items-center">
                                                                    @csrf
                                                                    @method('PATCH') 

                                                                    <select name="status" 
                                                                        class="status-select w-full max-w-[150px] rounded-lg border border-gray-300 dark:bg-gray-900 bg-gray-200 px-3 py-2 text-xs text-gray-900 dark:text-gray-100 
                                                                        dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition duration-200 ease-in-out"
                                                                        data-student-id="{{ $schedule->id }}">
                                                                        <option value="N/A" {{ $schedule->status === 'N/A' ? 'selected' : '' }}>N/A</option>
                                                                        <option value="present GRP" {{ $schedule->status === 'present GRP' ? 'selected' : '' }}>Present (GRP)</option>
                                                                        <option value="absent GRP" {{ $schedule->status === 'absent GRP' ? 'selected' : '' }}>Absent (GRP)</option>
                                                                        <option value="present MTM" {{ $schedule->status === 'present MTM' ? 'selected' : '' }}>Present (MTM)</option>
                                                                        <option value="absent MTM" {{ $schedule->status === 'absent MTM' ? 'selected' : '' }}>Absent (MTM)</option>
                                                                    </select>

                                                                    <button type="submit" 
                                                                        class="text-blue-500 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm cursor-pointer hover:underline">
                                                                        Update
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="text-gray-400 italic text-xs">You are not authorized to update this.</span>
                                                            @endif
                                                        @endrole

                                                        <span class="{{ $textColor }}">({{ $status }})</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 italic">---</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="teacherStudentsModalContainer"></div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @php
            use Carbon\Carbon;

            $today = Carbon::today();
            $tomorrow = Carbon::tomorrow();

            // Group schedules by schedule_date and sort by date descending
            $groupedByDate = $groupedSchedules->groupBy(fn($group) => $group->first()->schedule_date)
                ->sortKeysDesc();
        @endphp

        @foreach($groupedByDate as $date => $groups)
            @php
                $carbonDate = Carbon::parse($date);
                $dayLabel = $carbonDate->isToday() ? ' (Today)' : ($carbonDate->isTomorrow() ? ' (Tomorrow)' : '');
            @endphp

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 mt-8">
                 {{ $carbonDate->format('l, F j, Y') }}<span class="text-sm text-red-500">{{ $dayLabel }}</span>
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($groups as $index => $group)
                    @php
                        $schedule = $group->first();
                        $scheduleDate = Carbon::parse($schedule->schedule_date);

                        $borderColor = match(true) {
                            $scheduleDate->isToday() => 'border-green-500 ring-green-500/70',
                            $scheduleDate->isTomorrow() => 'border-yellow-500 ring-yellow-500/70',
                            default => 'border-red-200 dark:border-red-700 ring-red-500/70',
                        };
                    @endphp

                    <div 
                        class="group bg-white dark:bg-gray-800 rounded-2xl shadow transition hover:shadow-lg hover:ring-2 {{ $borderColor }} cursor-pointer"
                        onclick="showTeacherStudents({{ $schedule->teacher->user->id }}, '{{ $schedule->schedule_date }}')"
                    >
                        <div class="p-5 space-y-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                👨‍🏫 Teacher:
                                <span class="text-blue-600 dark:text-blue-400 group-hover:underline">
                                    {{ $schedule->teacher->name ?? 'N/A' }}
                                </span>
                            </h3>

                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                🏫 Room:
                                <span class="font-medium">{{ $schedule->room->roomname ?? 'N/A' }}</span>
                            </p>

                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                📅 Date:
                                <span class="font-medium">{{ $schedule->schedule_date }}</span>
                                <span class="text-red-500 font-semibold">
                                    ({{ $scheduleDate->format('l') }})
                                </span>
                            </p>

                            <p class="text-xs text-gray-400 dark:text-gray-500 italic pt-2">Click to view full schedule</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<div id="teacherStudentsModalContainer"></div> --}}

@include('components.alerts.success')

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
