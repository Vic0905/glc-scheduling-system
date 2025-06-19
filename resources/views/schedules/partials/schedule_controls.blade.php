
<div class="bg-gray-100 shadow-sm sm:rounded-lg p-6">
    <div class="flex flex-wrap md:flex-nowrap justify-center items-center gap-4 w-full">
        @role('admin')
        <!-- All admin controls in one flex row -->
        <div class="flex flex-wrap md:flex-nowrap justify-center items-center gap-3 w-full">

            <!-- Search Form -->
            <form action="{{ route('schedules.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full max-w-sm">
                <div class="relative w-full text-gray-800 uppercase font-bold">
                    <input type="text" name="teacher_name" value="{{ request('teacher_name') }}"
                        placeholder="Search by teacher name"
                        class="block w-full px-3 py-2 pl-10 text-gray-800 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"/>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                        </svg>
                    </div>
                </div>
                <button class="bg-gray-900 hover:bg-transparent px-3 py-1.5 text-xs shadow-sm hover:shadow-lg font-medium tracking-wider
                                border-2 border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition duration-100"
                    type="submit">Generate</button>
            </form>

            <!-- Date Form -->
            <form method="GET" action="{{ route('schedules.index') }}" class="flex flex-col md:flex-row items-center gap-2 w-full max-w-sm">
                <input type="date" id="date" name="date" value="{{ $date }}"
                    class="block w-full px-3 py-2 text-gray-800 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"/>
                <button class="bg-gray-900 hover:bg-transparent px-3 py-1.5 text-xs shadow-sm hover:shadow-lg font-medium tracking-wider
                                border-2 border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition duration-100"
                    type="submit">Generate</button>
            </form>

            <!-- Admin Buttons -->
            <div class="flex flex-wrap md:flex-nowrap gap-2">
                {{-- <a href="{{ route('schedules.available') }}" class="bg-gray-900 hover:bg-transparent px-3 py-1.5 text-xs shadow-sm hover:shadow-lg font-medium tracking-wider
                            border-2 border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition duration-100">
                    Schedules for {{ \Carbon\Carbon::today()->format('F d, Y') }}
                </a> --}}
                <a href="{{ route('schedules.input') }}" class="bg-gray-900 hover:bg-transparent px-3 py-1.5 text-xs shadow-sm hover:shadow-lg font-medium tracking-wider
                            border-2 border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition duration-100">
                    Input Schedules
                </a>
            </div>
        </div>
        @endrole
    </div>

    @role('teacher')
    <div class="w-full flex justify-center">
      <form action="{{ route('schedules.index') }}" method="GET"
            class="w-full max-w-5xl mx-auto flex flex-col md:flex-row md:items-center gap-4 p-4">

            <!-- 🔍 Search by Student Name -->
            <div class="relative w-full md:w-64">
                <label class="sr-only" for="student_name">Search by student name</label>
                <input type="text" name="student_name" id="student_name"
                    value="{{ request('student_name') }}"
                    placeholder="Search by student name"
                    class="w-full px-4 py-3 pl-10 text-sm text-gray-800 bg-white border border-gray-300 rounded-xl 
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" />
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                    </svg>
                </div>
            </div>

            <!-- 🔘 Search Button -->
            <div class="w-full md:w-auto">
                <button type="submit"
                    class="w-full md:w-auto bg-gray-900 hover:bg-transparent px-5 py-2 text-sm font-medium shadow-sm hover:shadow-lg tracking-wider border-2 
                    border-gray-200 hover:border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition ease-in duration-100">
                    Search
                </button>
            </div>
        </form>
    </div>
    @endrole
    
</div>