<x-app-layout>
    <x-slot name="header">
         <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight tracking-tight">
            {{ __('Subjects') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg p-6">
            <div class="flex flex-wrap md:flex-nowrap justify-center items-center p-5 space-x-4 w-full">
                <form action="{{ route('subjects.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full max-w-sm">
                    <!-- Search Input -->
                    <div class="relative w-full text-gray-800 dark:text-white uppercase font-bold">
                        <input
                            type="text"
                            name="subject_name"
                            value="{{ request('subject_name') }}"
                            placeholder="Search by Subject Name"
                            class="block w-full px-3 py-2 pl-10 text-gray-800 dark:text-white dark:bg-gray-900 bg-white border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
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
                        class="bg-gray-800 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                        border-2 border-gray-200 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100"
                        type="submit">
                        Search
                    </button>
                </form>
                @role('admin')
                <div class="flex justify-left">
                    <!-- Add Subject Button -->
                    <button onclick="openCreateModal()" 
                        class="bg-gray-800 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                        Add Subject
                    </button>
                </div>
                @endrole
            </div>

            <!-- Subjects Table -->
            <div class="overflow-x-auto overflow-y-auto max-h-96 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <table class="min-w-full table-auto border-collapse border border-gray-300 dark:border-gray-700">
                    <thead class="sticky top-0 bg-slate-100 dark:bg-gray-800 text-gray-900 dark:text-white z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Subject Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @foreach($subjects->sortBy('subjectname') as $subject)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">{{ $subject->subjectname }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">
                                    <div class="flex justify-start gap-2">
                                        <!-- Edit Button -->
                                        <button type="button" onclick="openEditModal({{ $subject->id }}, '{{ $subject->subjectname }}')"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition flex items-center gap-1 text-sm font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H5v-4l10-10z" />
                                            </svg>
                                            Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"  
                                                class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition flex items-center gap-1 text-sm font-medium">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                {{ $subjects->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>


    {{-- Include the edit subject modal --}}
    @include('subjects.partials.edit-subject-modal')
    @include('subjects.partials.create-subject-modal')
    @include('components.alerts.success')

    <script>

        function openCreateModal() {
            const modal = document.getElementById('createSubjectModal')
            modal.classList.remove('hidden');

            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.firstElementChild.classList.remove('scale-95');
            });
        }
        function closeCreateModal() {
            modal = document.getElementById('createSubjectModal')
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Open the edit modal and populate its form
        function openEditModal(subjectId, subjectName) {
            const modal = document.getElementById('editSubjectModal');
            const form = document.getElementById('editSubjectForm');
            const input = document.getElementById('modalSubjectName');

            // Set the form action to the correct update route for this subject
            form.action = `/subjects/${subjectId}`; // Assumes subjects.update route is /subjects/{id}

            // Populate the input field with the current subject name
            input.value = subjectName;

            // Show the modal
            modal.classList.remove('hidden');

            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.firstElementChild.classList.remove('scale-95');
            });
        }

        // Close the edit modal
        function closeEditModal() {
            modal = document.getElementById('editSubjectModal');
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-app-layout>