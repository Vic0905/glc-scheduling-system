<div class="py-2">
    <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 sm:rounded-none p-0 shadow-none border border-gray-300 dark:border-gray-700">
            <div class="overflow-auto max-w-full max-h-[700px] text-sm font-sans border-t border-l border-gray-300 dark:border-gray-700">
                <table class="min-w-full border-separate border-spacing-0 text-xs sm:text-xs md:text-base lg:text-xs text-gray-900 dark:text-white">
                    <thead class="sticky top-0 z-10 bg-gray-100 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">Teacher</th>
                            <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">Schedule Date</th>
                            @php
                                $timeSlots = [
                                    '08:00-08:50', '09:00-09:50', '10:00-10:50', '11:00-11:50', '12:00-12:50',
                                    '13:00-13:50', '14:00-14:50', '15:00-15:50', '16:00-16:50', '17:00-17:50',
                                    '18:00-18:50', '19:00-19:50', '20:00-20:50', '21:00-21:50',
                                    
                                ];
                            @endphp
                            @foreach($timeSlots as $slot)
                                <th class="border border-gray-300 dark:border-gray-600 bg-slate-100 dark:bg-gray-800 px-3 py-2">
                                    {{ str_replace('-', ' to ', $slot) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            use Carbon\Carbon;

                            $start = isset($startDate) ? Carbon::parse($startDate) : Carbon::now();
                            $end = isset($endDate) ? Carbon::parse($endDate) : $start;

                            $dates = [];
                            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                                $dates[] = $date->format('Y-m-d');
                            }

                            $groupedByDate = $groupedSchedules->groupBy(fn($g) => $g->first()->schedule_date);
                        @endphp

                        @foreach($dates as $date)
                            @php
                                $groups = $groupedByDate[$date] ?? collect();
                                $rowCount = $groups->count();
                                $formattedDate = Carbon::parse($date)->format('l');
                            @endphp

                            @if($groups->isEmpty())
                                <tr class="text-center text-sm">
                                    <td class="border px-4 py-2">No Schedule</td>
                                    <td class="border px-4 py-2" colspan="11">{{ $date }} ({{ $formattedDate }})</td>
                                </tr>
                            @else
                                @foreach($groups as $i => $group)
                                    <tr class="hover:bg-green-50 dark:hover:bg-gray-900 transition text-center text-xs align-middle">
                                        <td class="px-4 py-2 border-t border-r border-gray-300 dark:border-gray-700 w-40 max-w-[160px]">
                                            {{ $group->first()->teacher->name ?? 'N/A' }}
                                        </td>

                                        @if ($i === 0)
                                            <td class="px-4 py-2 border-t border-r border-gray-400 dark:border-gray-700 font-bold w-36 max-w-[140px] break-words align-middle bg-slate-50 dark:bg-gray-800 text-sm" rowspan="{{ $rowCount }}">
                                                {{ Carbon::parse($date)->format('F j, Y') }}
                                                <br>
                                                <span class="text-red-500">({{ $formattedDate }})</span>
                                            </td>
                                        @endif

                                        @foreach($timeSlots as $slot)
                                            @php
                                                $scheduledStudents = $group->filter(fn($s) => $s->time_slot === $slot);
                                            @endphp
                                            <td class="px-2 py-2 border-t border-r border-gray-300 dark:border-gray-700 w-56 max-w-[220px]">
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
                                                                <span class="font-medium whitespace-nowrap">
                                                                    {{ $schedule->student->name ?? 'N/A' }}
                                                                </span>
                                                                <span class="whitespace-nowrap">
                                                                    {{ $schedule->subject->subjectname ?? 'N/A' }}
                                                                </span>
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
                                                    <span class="text-gray-400 dark:text-gray-500 italic align-middle">No Schedule</span>
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
