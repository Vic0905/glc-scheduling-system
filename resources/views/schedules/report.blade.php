<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Schedule Report') }}
        </h2>
    </x-slot>

    <div class="flex flex-wrap items-center gap-4 mt-8 mb-8">
        <!-- Search Form -->
        <form action="{{ route('schedules.report') }}" method="GET" class="flex items-center space-x-3 w-full max-w-3xl">
            <div class="relative w-full max-w-sm">
                <input
                    type="text"
                    name="student_name"
                    value="{{ request('student_name') }}"
                    placeholder="Search by student name"
                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                />
                <div class="absolute inset-y-0 left-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                    </svg>
                </div>
            </div>
            <button class="px-6 py-2 text-xs font-medium text-white bg-gray-900 border-2 border-gray-500 rounded-xl hover:bg-transparent hover:text-gray-900 transition">
                Search
            </button>
        </form>
    
        <!-- Date Selection Form -->
        <form method="GET" action="{{ route('schedules.report') }}" class="flex items-center space-x-3 w-full max-w-sm">
            <input 
                type="date" 
                name="date" 
                value="{{ $date }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            />
            <button class="px-6 py-2 text-xs font-medium text-white bg-gray-900 border-2 border-gray-500 rounded-xl hover:bg-transparent hover:text-gray-900 transition">
                Generate
            </button>
        </form>
    </div>
    


    <!-- Message when no schedules found -->
    @if($schedules->isEmpty())
        <p>No schedules found for {{ $date }}.</p>
    @else

        <!-- Table to display schedules -->
        <div class="bg-white shadow-lg sm:rounded-lg overflow-x-auto max-w-auto rounded-lg max-w-full max-h-[500px] overflow-y-auto text-xs ">
            <table class="min-w-full border-collapse border border-gray-300 table-auto">
                <thead class="bg-gray-800 text-white sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm border-b">Student</th>
                        <th class="px-6 py-3 text-left text-sm border-b">Student Room</th>
                        <th class="px-6 py-3 text-left text-sm border-b">Teacher</th>
                        <th class="px-6 py-3 text-left text-sm border-b">Room</th>
                        <th class="px-6 py-3 text-left text-sm border-b">Subject</th>
                        <th class="px-6 py-3 text-left text-sm border-b">Schedule Date</th>
                        <th class="px-6 py-3 text-left text-sm border-b">Schedule Time</th>
                        <th class="px-6 py-3 text-left text-sm border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- handles the logics of the reports of the schedules --}}
                    @foreach($schedules as $schedule)
                    <tr class="hover:bg-gray-100 {{ $schedule->deleted_at ? 'bg-red-100' : '' }}">
                        <td class="px-6 py-4 border-b">
                            {{ $schedule->student->name ?? 'N/A' }}
                            @if($schedule->deleted_at)
                                <span class="text-red-500">(Deleted)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 border-b">{{ $schedule->studentRoom->roomname ?? 'N/A' }}</td>
                        <td class="px-6 py-4 border-b">{{ $schedule->teacher->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 border-b">{{ $schedule->room->roomname ?? 'N/A' }}</td>
                        <td class="px-6 py-4 border-b">{{ $schedule->subject->subjectname ?? 'N/A' }}</td>
                        <td class="px-6 py-4 border-b">{{ $schedule->schedule_date ?? 'N/A' }}</td>

                             {{-- Display only assigned time slots --}}
                             @php
                             $assignedSlots = [
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
                     
                         @foreach($assignedSlots as $time => $slot)
                             @if($schedule->{$slot})  {{-- Show only if scheduled --}}
                                 <td class="px-6 py-4 border-b">
                                     <span class="text-gray-900">{{ $time }} - {{ substr($time, 0, -1) }}0</span>
                                 </td>
                             @endif
                         @endforeach

                        {{-- this function will enable to see the status of the student based on the status modified by the teacher --}}
                        <td class="px-6 py-4 border-b text-gray-800">
                            @if($schedule->status == 'present GRP' || $schedule->status == 'present MTM')
                                <span class="text-green-600">{{ $schedule->status }}</span>
                            @elseif($schedule->status == 'absent GRP' || $schedule->status == 'absent MTM' || $schedule->status == 'N/A')
                                <span class="text-red-600">{{ $schedule->status }}</span>
                            @else
                                <span class="text-gray-500">{{ $schedule->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
              <!-- Pagination controls -->
              <div class="flex justify-end">
                {{ $schedules->links('vendor.pagination.tailwind') }}
            </div>
    @endif
</div>

<script>
      // function to make the newest schedules appear at the top of the table
      document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('tbody');
    const rows = Array.from(tableBody.querySelectorAll('tr'));
    rows.reverse().forEach(row => tableBody.appendChild(row));
});
</script>
</x-app-layout>
