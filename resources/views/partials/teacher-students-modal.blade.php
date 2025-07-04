
<!-- Teacher's Students Modal -->
<div id="teacherStudentsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-4 sm:p-5 md:p-6 w-full sm:w-[600px] md:w-[900px] lg:w-[1100px] max-w-[1200px] max-h-[75vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">{{ $teacher->name }}'s Students</h2>
            <button onclick="closeTeacherStudentsModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y dark:bg-gray-900 divide-gray-200 rounded-lg">
                <thead class="bg-gray-200 dark:bg-gray-900 rounded-md">
                    <tr>
                        <th class="dark:text-gray-100 px-2 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Student Name</th>
                        <th class="dark:text-gray-100 px-2 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Subject</th>
                        <th class="dark:text-gray-100 px-2 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Schedule Date</th>
                        <th class="dark:text-gray-100 px-2 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Schedule Time</th>
                        <th class="dark:text-gray-100 px-2 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Status</th>
                        
                        @role('admin')
                        <th class="px-6 py-3 text-left text-xs font-medium dark:text-gray-100 text-gray-800 uppercase tracking-wider">Action</th> 
                        @endrole
                        @role('teacher')
                        <th class="px-8 py-3 ml-8 text-xs font-medium dark:text-gray-100 text-gray-800 uppercase tracking-wider">Action</th>
                        @endrole

                    </tr>
                </thead>
                <tbody class="dark:bg-gray-700 bg-white divide-y divide-gray-300 px-6 py-4">
                    @forelse ($students as $student)
                        <tr>
                                <td class="dark:text-gray-100 dark:bg-gray-900 px-2 py-4 text-xs  whitespace-nowrap bg-gray-100">{{ optional($student->student)->name ?? 'N/A' }}</td>
                                <td class="dark:text-gray-100 dark:bg-gray-900 px-2 py-4 text-xs  whitespace-nowrap bg-gray-100">{{ optional($student->subject)->subjectname ?? 'N/A' }}</td>
                                <td class="dark:text-gray-100 dark:bg-gray-900 px-2 py-4 text-xs  whitespace-nowrap bg-gray-100">{{ $student->schedule_date ?? 'N/A' }}</td>
                                <td class="dark:text-gray-100 dark:bg-gray-900 px-2 py-4 text-xs  font-bold whitespace-nowrap bg-gray-100">{{ $student->schedule_time ?? 'N/A' }}</td>
                                <td class="dark:text-gray-100 dark:bg-gray-900 px-2 py-4 text-xs  whitespace-nowrap bg-gray-100">
                                    <span class="px-2 inline-flex text-s leading-5 font-semibold rounded-full {{ Str::contains($student->status, 'present') ? 'bg-green-100 text-green-900' : 'bg-red-100 text-red-900' }}">
                                        {{ $student->status ?? 'N/A' }}
                                    </span>
                                </td>
                                @role('admin')
                                <td class="px-2 py-4 whitespace-nowrap dark:text-gray-100 dark:bg-gray-900 bg-gray-100">
                                  <!-- Edit Button -->
                                    <a href="{{ route('schedules.edit', $student->id) }}"
                                    class="group inline-flex items-center text-blue-500 hover:text-blue-700 text-sm cursor-pointer ml-1">
                                        <!-- Edit Icon (Pencil) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H5v-4l10-10z" />
                                        </svg>
                                        <span class="ml-1">Edit</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <form id="delete-form-{{ $student->id }}"
                                        action="{{ route('schedules.destroy', $student->id) }}"
                                        method="POST"
                                        class="inline-block ml-3">
                                        @csrf
                                        @method('DELETE') 
                                        <button type="button"
                                                onclick="confirmDelete({{ $student->id }})"
                                                class="group inline-flex items-center text-red-500 hover:text-red-700 text-sm cursor-pointer">
                                            <!-- Delete Icon (Trash) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22" />
                                            </svg>
                                            <span class="ml-1">Delete</span>
                                        </button>
                                    </form>
                                </td>
                            @endrole
                            
                            @role('teacher')
                            <td class="px-2 py-4 whitespace-nowrap bg-gray-100 text-center">
                                <form action="{{ route('schedules.updateStatus', ['id' => $student->id]) }}" method="POST" class="flex flex-col gap-2 items-center">
                                    @csrf
                                    @method('PATCH') 
                            
                                    <!-- Dropdown -->
                                    <select name="status" 
                                        class="status-select w-full max-w-[150px] rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition duration-200 ease-in-out"
                                        data-student-id="{{ $student->id }}">
                                        <option value="N/A" {{ $student->status === 'N/A' ? 'selected' : '' }}>N/A</option>
                                        <option value="present GRP" {{ $student->status === 'present GRP' ? 'selected' : '' }}>Present (GRP)</option>
                                        <option value="absent GRP" {{ $student->status === 'absent GRP' ? 'selected' : '' }}>Absent (GRP)</option>
                                        <option value="present MTM" {{ $student->status === 'present MTM' ? 'selected' : '' }}>Present (MTM)</option>
                                        <option value="absent MTM" {{ $student->status === 'absent MTM' ? 'selected' : '' }}>Absent (MTM)</option>
                                    </select>
                            
                                    <!-- Button -->
                                    <button type="submit" 
                                        class="text-gray-500 hover:text-gray-700 text-sm cursor-pointer hover:underline">
                                        Update
                                    </button>
                                </form>
                            </td>
                            @endrole              
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No students found for this schedule</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



