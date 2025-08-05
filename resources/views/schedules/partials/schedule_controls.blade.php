<div class="flex flex-col md:flex-row md:justify-between items-center p-4 gap-3 w-full max-w-6xl mx-auto md:items-end">
    <!-- Admin View -->
    @role('admin') 
    <div class="w-full overflow-x-auto">
        <form action="{{ route('schedules.index') }}" method="GET"
            class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full md:w-auto">

            <!-- Teacher Name -->
           {{-- <div class="flex flex-col w-full md:w-[18%]">
            <label for="teacher_name" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Teacher Name
            </label>
            <div class="relative">
                <input
                    type="text"
                    name="teacher_name"
                    id="teacher_name"
                    value="{{ request('teacher_name') }}"
                    placeholder="Search teacher or sub name"
                    class="w-full px-3 py-2 pl-10 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-gray-900 dark:text-gray-100"
                />
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35"/>
                    </svg>
                </div>
            </div>
        </div> --}}

 
            <div class="flex flex-col text-sm">
                <label for="schedule_date" class="text-gray-700 dark:text-gray-200 font-semibold mb-1">
                    Filter Date
                </label>
            <input 
                    type="date" 
                    id="schedule_date" 
                    name="schedule_date" 
                    value="{{ request('schedule_date') ?? now()->format('Y-m-d') }}"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" 
                />
            </div>


            <div class="flex flex-col justify-end">
                <label class="invisible">Filter</label>
                <button type="submit"
                    class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                    border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                    Filter
                </button>
            </div>

            {{-- <a href="{{ route('schedules.available') }}"
            class="inline-flex items-center justify-center mt-5
                    bg-gray-800 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                Schedule Today: {{ \Carbon\Carbon::today()->format('F d, Y') }}
            </a> --}}

            <div class="flex justify-end w-full mt-6">
                <a href="{{ route('schedules.input') }}"
                    class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                        border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100"> 
                    Input Schedules
                </a>
            </div>
        </form>
    </div>
    @endrole

    <!-- Teacher View -->
    @role('teacher')
    <div class="w-full flex justify-center">
        <form action="{{ route('schedules.index') }}" method="GET"
            class="w-full max-w-5xl mx-auto flex flex-col md:flex-row md:flex-wrap items-end gap-4 p-4">

            <!-- Student Name Input -->
            <div class="flex flex-col w-full md:w-[25%]">
                <label for="student_name" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Student Name</label>
                <div class="relative">
                    <input type="text" name="student_name" id="student_name" value="{{ request('student_name') }}"
                        placeholder="Search by student name"
                        class="w-full px-3 py-2 pl-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-gray-900 dark:text-gray-100"/>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                </div>
            </div>

              <div class="flex flex-col text-sm">
                <label for="schedule_date" class="text-gray-700 dark:text-gray-200 font-semibold mb-1">
                    Filter Date
                </label>
            <input 
                    type="date" 
                    id="schedule_date" 
                    name="schedule_date" 
                    value="{{ request('schedule_date') ?? now()->format('Y-m-d') }}"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" 
                />
            </div>


            <div class="flex flex-col justify-end">
                <label class="invisible">Filter</label>
                <button type="submit"
                    class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                    border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                    Filter
                </button>
            </div>

        </form>
    </div>
    @endrole

</div>
