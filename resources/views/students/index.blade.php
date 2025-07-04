<x-app-layout>
    <x-slot name="header">
         <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight tracking-tight"> 
            {{ __('Students') }}
        </h2>
    </x-slot>

    <div class="py-12 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <div class="flex flex-wrap md:flex-nowrap justify-center items-center p-5 space-x-4 w-full">
                <form action="{{ route('students.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full max-w-sm">
                    <!-- Search Input -->
                    <div class="relative w-full text-gray-800 dark:text-white uppercase font-bold">
                        <input
                            type="text"
                            name="student_name"
                            value="{{ request('student_name') }}"
                            placeholder="Search by Student Name"
                            class="block w-full px-3 py-2 pl-10 text-gray-800 dark:text-gray-100 dark:bg-gray-700 bg-white border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                        />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 text-gray-500 dark:text-gray-300"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                            </svg>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <button
                        class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100"
                        type="submit">
                        Search
                    </button>
                </form>
                @role('admin')
                <div class="flex justify-left">
                    <!-- Add Student Button (Right) -->
                    <button onclick="openCreateModal()" 
                        class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                        Add Student
                    </button>
                </div>
                @endrole
            </div>
            <!-- Students Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-xl">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-slate-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">English Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $student->english_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $student->course }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $student->level }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                        <!-- Edit Button -->
                                        <button type="button" onclick="openEditModal({{ $student->id }}, '{{ $student->name }}', '{{ $student->english_name }}', '{{ $student->course }}', '{{ $student->level }}')"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition flex items-center gap-1 text-sm font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H5v-4l10-10z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-200 transition flex items-center gap-1 text-sm font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300 text-sm">
                                    No students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


    @include('students.partials.edit-student-modal')
    @include('students.partials.create-student-modal')
    @include('components.alerts.success')

    <script>

     function openCreateModal() {
        const modal = document.getElementById('createRoomModal');
        modal.classList.remove('hidden');

        // Allow transition after next paint
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
        });
    }

    function closeCreateModal() {
        const modal = document.getElementById('createRoomModal');
        modal.classList.add('opacity-0');
        modal.firstElementChild.classList.add('scale-95');

        // Wait for transition before hiding
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function openEditModal(studentId, name, englishName, course, level) {
        const modal = document.getElementById('editStudentModal');
        const form = document.getElementById('editStudentForm');
        const input = document.getElementById('modalName');
        const englishInput = document.getElementById('modalEnglishName');
        const courseInput = document.getElementById('modalCourse');
        const levelInput = document.getElementById('modalLevel');

        form.action = `/students/${studentId}`;
        input.value = name;
        englishInput.value = englishName;
        courseInput.value = course;
        levelInput.value = level;

        modal.classList.remove('hidden');

        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
        });
    }
    function closeEditModal() {
        const modal = document.getElementById('editStudentModal');
        modal.classList.add('opacity-0');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
     
    </script>
</x-app-layout>
