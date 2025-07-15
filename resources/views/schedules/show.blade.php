<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Available Schedules') }}
        </h2>
    </x-slot>

 <div class="flex flex-wrap md:flex-nowrap justify-center items-center p-5 space-x-4 w-full">
    @role('admin')
        <x-search-input
            :action="route('schedules.available')"
            placeholder="Search by student name"
            name="student_name"
        />
    @elserole('teacher')
        <x-search-input
            :action="route('schedules.available')"
            placeholder="Search by student name"
            name="student_name"
        />
    @endrole

    <!-- Add Schedule Button (Centered on Mobile) -->
    <div class="flex justify-center w-full">
        <a href="{{ route('schedules.index') }}" 
           class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
            Back to Schedules
        </a>
    </div>  
</div>

            
<div class="py-2">
    <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-x-auto"></div>
        <div class="dark:bg-gray-800 bg-white shadow-sm sm:rounded-lg p-4">
            <div class="dark:bg-gray-900 bg-white rounded-md overflow-hidden max-w-full max-h-[600px] overflow-y-auto text-sm font-sans border border-gray-300 dark:border-gray-700">
                <table class="min-w-full border border-gray-300 dark:border-gray-700 text-xs font-medium border-collapse">
                    <thead class="sticky top-0 z-10 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-white">
                        <!-- Date Header Row -->
                        <tr>
                            <th colspan="13" class="text-red-600 dark:text-white text-center text-base font-semibold px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700">
                                Schedules for {{ \Carbon\Carbon::today()->format('F d, Y') }}
                            </th>
                        </tr>
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 px-2 py-2">Teacher</th>
                            <th class="border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 px-2 py-2">Room</th>
                            @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                                @php
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $time);
                                    $endTime = $startTime->copy()->addMinutes(50);
                                @endphp
                                <th class="border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 px-2 py-1 whitespace-nowrap">
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
                            <tr class="hover:bg-green-50 dark:hover:bg-gray-900 text-center align-top transition">
                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center">
                                    <a href="#" onclick="event.preventDefault(); showTeacherStudents({{ $group->first()->teacher->user->id }}, '{{ $group->first()->schedule_date }}')" 
                                       class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $group->first()->teacher->name ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-2 font-semibold text-center">
                                    {{ $group->first()->room->roomname ?? 'N/A' }}
                                </td>

                                @foreach($timeSlots as $time => $slotKey)
                                    @php
                                        $scheduledStudents = $group->filter(fn($schedule) => $schedule->{$slotKey});
                                    @endphp
                                    <td class="border border-gray-300 dark:border-gray-600 px-1 py-1 text-center align-top">
                                        @if($scheduledStudents->isNotEmpty())
                                            @foreach($scheduledStudents as $schedule)
                                                @php
                                                    $status = $schedule->status ?? 'N/A';
                                                    $isAbsent = in_array($status, ['N/A', 'absent GRP', 'absent MTM']);
                                                    $bgColor = $isAbsent ? 'bg-red-100 dark:bg-red-900' : 'bg-green-100 dark:bg-green-900';
                                                    $textColor = $isAbsent ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300';
                                                @endphp
                                                <div class="rounded px-2 py-1 mb-1 {{ $bgColor }}">
                                                    <span class="block font-semibold text-xs">{{ $schedule->student->name ?? 'N/A' }}</span>
                                                    <span class="block text-xs {{ $textColor }}">({{ $status }})</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 italic text-xs">---</span>
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



    <script>
        // Function to show the teacher's students modal 
        function showTeacherStudents(teacherId, scheduleDate) {
        fetch(`/teachers/${teacherId}/students/${scheduleDate}`)
            .then(response => response.text()) // Fetch as HTML
            .then(html => {
                document.getElementById('teacherStudentsModalContainer').innerHTML = html;
                document.getElementById('teacherStudentsModal').classList.remove('hidden');
                attachStatusChangeListeners(); // Attach event listeners for status change
            })
            .catch(error => {
                console.error('Error loading students:', error);
                alert('Error loading students. Please check the console.');
            });
        }

        function closeTeacherStudentsModal() {
            document.getElementById('teacherStudentsModal').classList.add('hidden');
        }


        // Add Event Listeners to Status Select Dropdowns
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

</x-app-layout>
