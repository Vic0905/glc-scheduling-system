<div class="bg-gray-100 shadow-sm sm:rounded-lg p-6">
    <!-- Admin View -->
    @role('admin')
    <div class="w-full overflow-x-auto">
        <form action="{{ route('schedules.index') }}" method="GET"
            class="flex flex-col md:flex-row md:items-end md:flex-wrap gap-4 w-full max-w-7xl mx-auto p-4">

            <!-- Teacher Name -->
            <div class="flex flex-col w-full md:w-[18%]">
                <label for="teacher_name" class="text-sm font-semibold text-gray-700 mb-1">Teacher Name</label>
                <div class="relative">
                    <input type="text" name="teacher_name" id="teacher_name" value="{{ request('teacher_name') }}"
                        placeholder="Search teacher name"
                        class="w-full px-3 py-2 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Start Date -->
            <div class="flex flex-col w-full md:w-[15%]">
                <label for="start_date" class="text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" />
            </div>

            <!-- End Date -->
            <div class="flex flex-col w-full md:w-[15%]">
                <label for="end_date" class="text-sm font-semibold text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" />
            </div>

            <!-- Generate Button -->
            <div class="flex flex-col w-full md:w-auto pt-1 md:pt-5">
                <button type="submit"
                        class="bg-gray-900 hover:bg-transparent px-5 py-2 text-sm font-semibold border-2 border-gray-200 text-white hover:text-gray-900 rounded-lg shadow-sm hover:shadow-lg transition duration-150">
                    Generate
                </button>
            </div>

            <a href="{{ route('schedules.available') }}"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-gray-900 border-2 border-gray-200 rounded-lg shadow-sm transition duration-150
                    hover:bg-transparent hover:text-gray-900 hover:shadow-md">
                Schedule Today: {{ \Carbon\Carbon::today()->format('F d, Y') }}
            </a>

            <a href="{{ route('schedules.input') }}"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-gray-900 border-2 border-gray-200 rounded-lg shadow-sm transition duration-150
                    hover:bg-transparent hover:text-gray-900 hover:shadow-md">
                Input Schedules
            </a>
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
                <label for="student_name" class="text-sm font-semibold text-gray-700 mb-1">Student Name</label>
                <div class="relative">
                    <input type="text" name="student_name" id="student_name" value="{{ request('student_name') }}"
                        placeholder="Search by student name"
                        class="w-full px-3 py-2 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"/>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Start Date -->
            <div class="flex flex-col w-full md:w-[15%]">
                <label for="start_date" class="text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"/>
            </div>

            <!-- End Date -->
            <div class="flex flex-col w-full md:w-[15%]">
                <label for="end_date" class="text-sm font-semibold text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"/>
            </div>

            <!-- Generate Button -->
            <div class="flex flex-col w-full md:w-auto pt-1 md:pt-5">
                <button type="submit"
                        class="bg-gray-900 hover:bg-transparent px-5 py-2 text-sm font-semibold border-2 border-gray-200 text-white hover:text-gray-900 rounded-lg shadow-sm hover:shadow-lg transition duration-150">
                    Generate
                </button>
            </div>

            <!-- Today's Schedule Button -->
            <div class="flex flex-col w-full md:w-auto pt-1 md:pt-5">
                <a href="{{ route('schedules.available') }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-gray-900 border-2 border-gray-200 rounded-lg shadow-sm transition duration-150
                        hover:bg-transparent hover:text-gray-900 hover:shadow-md">
                    Schedule Today: {{ \Carbon\Carbon::today()->format('F d, Y') }}
                </a>
            </div>
        </form>
    </div>
    @endrole

</div>
