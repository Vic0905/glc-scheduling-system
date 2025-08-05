    @php
        use Carbon\Carbon;

        $timeSlots = [
            '08:00-08:50', '09:00-09:50', '10:00-10:50', '11:00-11:50', '12:00-12:50',
            '13:00-13:50', '14:00-14:50', '15:00-15:50', '16:00-16:50', '17:00-17:50',
            '18:00-18:50', '19:00-19:50', '20:00-20:50', '21:00-21:50',
        ];

        $dates = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekday()) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        $groupedByDate = $groupedSchedules->groupBy(fn($g) => $g->first()->schedule_date);
        
    @endphp

        <div class="flex justify-center items-center px-4 bg-slate-100 dark:bg-gray-900">
            @if (request('schedule_date'))
                <h2 class="text-3xl font-bold text-orange-700 dark:text-orange-400 mb-2">
                {{ \Carbon\Carbon::parse(request('schedule_date'))->format('F j') }} - {{ $end->format('j, Y') }}
                </h2>
            @else
                <h2 class="text-3xl font-bold text-orange-700 dark:text-orange-400 mb-2">
                    {{ $start->format('F j') }} - {{ $end->format('j, Y') }}
                </h2>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-900 sm:rounded-none p-0 shadow-none border border-gray-300 dark:border-gray-700">
            <div class="overflow-auto max-w-full max-h-[700px] text-sm font-sans border-t border-l border-gray-300 dark:border-gray-700">
                <table class="min-w-full border-separate border-spacing-0 text-xs md:text-sm text-gray-900 dark:text-white">
                    <thead class="sticky top-0 z-10 bg-gray-100 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                        <tr>
                            <th class="border border-blue-600 dark:border-gray-600 bg-blue-800 dark:bg-gray-800 px-3 py-2 text-white text-xs font-semibold">Teacher</th>
                            @role('teacher')
                                <th class="border border-blue-600 dark:border-gray-600 bg-blue-800 dark:bg-gray-800 px-3 py-2 text-white text-xs font-semibold">Sched-Date</th>
                            @endrole
                            @foreach($timeSlots as $slot)
                                <th class="border border-blue-600 dark:border-gray-600 bg-blue-800 dark:bg-gray-800 px-3 py-2 text-white text-xs font-semibold">
                                    {{ str_replace('-', ' - ', $slot) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dates as $date)
                            @php
                                $groups = $groupedByDate[$date] ?? collect();
                                $rowCount = $groups->count();
                                $formattedDate = Carbon::parse($date)->format('l');
                            @endphp

                            @if($groups->isNotEmpty())
                                @foreach($groups as $i => $group)
                                    <tr class="hover:bg-green-50 dark:hover:bg-gray-800 transition text-center text-xs">
                                        <td class="px-4 py-2 border-t border-r border-gray-300 dark:border-gray-700 w-40 max-w-[160px] text-center">
                                            {{ $group->first()->teacher->name ?? 'N/A' }}
                                        </td>

                                        @role('teacher')
                                            @if ($i === 0)
                                                <td class="px-4 py-2 border-t border-r border-gray-400 dark:border-gray-700 font-bold w-36 max-w-[140px] break-words bg-slate-100 dark:bg-gray-800 text-sm text-center align-middle" rowspan="{{ $rowCount }}">
                                                    {{ Carbon::parse($date)->format('F j, Y') }}<br>
                                                    <span class="text-red-500">({{ $formattedDate }})</span>
                                                </td>
                                            @endif
                                        @endrole

                                        @foreach($timeSlots as $slot)
                                            @php
                                                $scheduledStudents = $group->filter(fn($s) => $s->time_slot === $slot);
                                            @endphp
                                            <td class="px-2 py-2 border-t border-r border-gray-300 dark:border-gray-700 align-middle w-56 max-w-[220px]">
                                                @if($scheduledStudents->isNotEmpty())
                                                    @foreach($scheduledStudents as $schedule)
                                                        @php
                                                            $status = $schedule->status ?? 'N/A';
                                                            $isAbsent = in_array($status, ['N/A', 'absent GRP', 'absent MTM']);
                                                            $textColor = $isAbsent ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300';
                                                            $bgColor = $isAbsent ? 'bg-red-50 dark:bg-gray-900' : 'bg-green-50 dark:bg-gray-900';
                                                            $roomName = $schedule->room->roomname ?? 'No Room';
                                                        @endphp
                                                        <div class="{{ $bgColor }} mb-1 p-2 rounded-md leading-tight text-[11px] sm:text-xs shadow-sm">
                                                            <div class="flex flex-col space-y-1">
                                                                <span class="font-medium whitespace-nowrap">{{ $schedule->student->name ?? 'N/A' }}</span>
                                                                <span class="whitespace-nowrap">{{ $schedule->subject->subjectname ?? 'N/A' }}</span>
                                                                <strong class="whitespace-nowrap text-[11px]">Room: {{ $roomName }}</strong>
                                                            </div>
                                                            <div class="mt-1">
                                                                @role('teacher')
                                                                    @php
                                                                        $subRoomId = $schedule->subTeacher->room->id ?? null;
                                                                        $mainRoomId = $schedule->teacher->room->id ?? null;
                                                                    @endphp
                                                                    @if ($subRoomId === $mainRoomId)
                                                                        <form action="{{ route('schedules.updateStatus', ['id' => $schedule->id]) }}" method="POST" class="flex flex-col items-center gap-1 mt-1">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <select name="status"
                                                                                class="w-full max-w-[150px] rounded-md border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-900 px-2 py-1 text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-gray-400 focus:outline-none transition">
                                                                                <option value="N/A" {{ $schedule->status === 'N/A' ? 'selected' : '' }}>N/A</option>
                                                                                <option value="present GRP" {{ $schedule->status === 'present GRP' ? 'selected' : '' }}>Present (GRP)</option>
                                                                                <option value="absent GRP" {{ $schedule->status === 'absent GRP' ? 'selected' : '' }}>Absent (GRP)</option>
                                                                                <option value="present MTM" {{ $schedule->status === 'present MTM' ? 'selected' : '' }}>Present (MTM)</option>
                                                                                <option value="absent MTM" {{ $schedule->status === 'absent MTM' ? 'selected' : '' }}>Absent (MTM)</option>
                                                                            </select>
                                                                            <button type="submit"
                                                                                class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-xs underline mt-1">
                                                                                Update
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <span class="text-gray-400 italic text-xs">Not authorized</span>
                                                                    @endif
                                                                @endrole
                                                                <span class="{{ $textColor }}">({{ $status }})</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500 italic">----</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div id="teacherStudentsModalContainer"></div>
            </div>
        </div>
    </div>
</div>
