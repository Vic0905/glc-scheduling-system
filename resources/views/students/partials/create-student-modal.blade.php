<!-- Edit Student Modal -->
<div id="createRoomModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="dark:bg-gray-900 bg-white rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-lg mx-4 transform scale-95 transition-all duration-300">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold dark:text-gray-100 text-gray-800" id="editModalTitle">Create Student</h2>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('students.store') }}" method="POST" action="" class="space-y-4">
            @csrf
            <!-- Student Name -->
            <div>
                <label for="modalName" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Student Name</label>
                <input
                    type="text"
                    name="name" 
                    id="modalName"
                    class="w-full px-4 py-2 border dark:bg-gray-900 dark:border-gray-700 border-gray-300 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    required
                >
            </div>

            <!-- English Name -->
            <div>
                <label for="modalEnglishName" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">English Name</label>
                <input
                    type="text"
                    name="english_name"
                    id="modalEnglishName"
                    class="w-full px-4 py-2 border dark:bg-gray-900 dark:border-gray-700 border-gray-300 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    required
                >
            </div>

            <!-- Course -->
            <div>
                <label for="modalCourse" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Course</label>
                <input
                    type="text"
                    name="course"
                    id="modalCourse"
                    class="w-full px-4 py-2 border dark:bg-gray-900 dark:border-gray-700 border-gray-300 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    required
                >
            </div>

            <!-- Level -->
            <div>
                <label for="modalLevel" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Level</label>
                <input
                    type="text"
                    name="level" 
                    id="modalLevel"
                    class="w-full px-4 py-2 border dark:bg-gray-900 dark:border-gray-700 border-gray-300 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    required
                >
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <button
                    type="button"
                    onclick="closeCreateModal()"
                    class="bg-gray-800 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-blue-400 text-gray-100 hover:text-blue-900 dark:text-white dark:hover:text-white rounded-lg transition ease-in duration-100"
                >
                    Add Student
                </button>
            </div>
        </form>
    </div>
</div>
