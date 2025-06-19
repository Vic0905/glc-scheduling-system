<div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-3 lg:px-4">
            <!-- Current Time -->
            <div class="text-right text-sm text-gray-600 font-medium">
                <span id="currentTime" class="font-semibold text-gray-900"></span>
            </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-5 w-full">
            <!-- Schedule Cards -->
            @foreach($rooms as $room)
                @php $roomSchedules = $schedulesByRoom[$room->roomname] ?? collect(); @endphp
                @if($roomSchedules->isNotEmpty())
                
                    <div class="bg-white rounded-2xl p-4 shadow-lg border border-slate-100 hover:shadow-md transition-all duration-200 ease-in-out cursor-pointer group hover:border-indigo-300">
                        <h2 class="text-md font-bold text-gray-800 mb-2">
                            Room: <span class="text-gray-800">{{ $room->roomname }}</span>
                        </h2>
                        @foreach($roomSchedules as $teacherDateKey => $group)
                            @php $first = $group->first(); @endphp
                            <div class="mb-2 border-l-4 border-indigo-500 pl-4">
                                <!-- Teacher and Date -->
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3">
                                    <div>
                                        <h3 class="text-md font-semibold text-gray-700">
                                            Teacher:
                                            <a href="#" onclick="event.preventDefault(); showTeacherStudents({{ $first->teacher->user->id ?? 0 }}, '{{ $first->schedule_date }}')" 
                                            class="text-indigo-600 hover:underline">
                                                {{ $first->teacher->name ?? 'N/A' }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">Date: {{ \Carbon\Carbon::parse($first->schedule_date)->format('F d, Y') }}</p>
                                    </div>
                                    @role('admin')
                                    <div class="mt-2 sm:mt-0">
                                        <a onclick="confirmDeleteByRoomAndDate({{ $first->room_id }}, '{{ $first->schedule_date }}')"
                                        class="text-red-500 hover:underline text-sm font-medium cursor-pointer">
                                            Delete All
                                        </a>
                                    </div>
                                    @endrole
                                </div>

                                <!-- Time Slot Cards -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                                    @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                                        @php
                                            $slotKey = $timeSlots[$time] ?? null;
                                            $filtered = $group->filter(fn($s) => $slotKey && $s->{$slotKey});
                                            $startTime = \Carbon\Carbon::createFromFormat('H:i', $time);
                                            $endTime = $startTime->copy()->addMinutes(50);
                                        @endphp

                                        @if($filtered->isNotEmpty())
                                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow transition">
                                                <p class="text-sm font-semibold text-gray-800 mb-2">
                                                    {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                                                </p>
                                                @foreach($filtered as $schedule)
                                                    @php
                                                        $status = $schedule->status ?? 'N/A';
                                                        $isAbsent = in_array($status, ['N/A', 'absent GRP', 'absent MTM']);
                                                        $statusClasses = $isAbsent
                                                            ? 'bg-red-100 text-red-700 border border-red-300'
                                                            : 'bg-green-100 text-green-700 border border-green-300';
                                                    @endphp
                                                    <div class="mb-2 p-2 rounded-md {{ $statusClasses }}">
                                                        <div class="font-medium text-sm">{{ $schedule->student->name ?? 'N/A' }}</div>
                                                        <div class="text-xs">{{ $status }}</div>
                                                        <div class="text-xs italic text-gray-600">{{ $schedule->subject->subjectname ?? 'N/A' }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<!-- Current Time Script -->
<script>
    function updateTime() {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleTimeString();
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>


