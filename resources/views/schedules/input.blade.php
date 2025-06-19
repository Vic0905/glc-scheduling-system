<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Input Schedule') }}
        
        </h2>
    </x-slot>

    <div class="flex flex-col md:flex-row md:justify-between items-center p-3 gap-2 w-full max-w-7xl mx-auto"> {{-- Added max-w-7xl mx-auto --}}

        @php
            $startDate = request('start_date') ?? now()->format('Y-m-d');
            $endDate = request('end_date') ?? now()->format('Y-m-d');
        @endphp

        <form action="{{ route('schedules.input') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto flex-grow">
            <div class="relative w-full md:w-48 text-gray-800 uppercase font-bold">
                <input type="text" name="teacher_name" value="{{ request('teacher_name') }}" placeholder="Search teacher"
                    class="block w-full px-3 py-2 pl-9 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out"
                    aria-label="Search by teacher name" />
                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div>
                    <label for="start_date" class="sr-only">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="p-2 text-xs border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        aria-label="Start Date" />
                </div>
                <div>
                    <label for="end_date" class="sr-only">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="p-2 text-xs border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        aria-label="End Date" />
                </div>
            </div>
        </form>

        <div class="flex-shrink-1 mt-3 md:mt-0">
            <a href="{{ route('schedules.index') }}"
                class="bg-gray-900 hover:bg-transparent px-4 py-2 text-xs shadow-sm hover:shadow-lg font-medium tracking-wider
                border-2 border-gray-200 hover:border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition ease-in duration-100">
                Back to Schedules
            </a>
        </div>
    </div>

@include('schedules.partials.schedule_input')


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submissions when dropdowns change
        document.querySelectorAll('.schedule-form select').forEach(select => {
            select.addEventListener('change', function() {
                const form = this.closest('form');
                const studentSelect = form.querySelector('select[name="student_id"]');
                const subjectSelect = form.querySelector('select[name="subject_id"]');
                
                const row = this.closest('tr');
                const teacherSelect = row.querySelector('.teacher-select'); // This exists only for empty rows
                let teacherId = null; // Initialize as null

                // Get teacher_id from hidden input for existing teacher rows, or from select for new rows
                const teacherIdInput = form.querySelector('input[name="teacher_id"]');
                if (teacherIdInput) {
                    teacherId = teacherIdInput.value;
                } else if (teacherSelect) { // Fallback for rows with the teacher dropdown
                    teacherId = teacherSelect.value;
                }

                // TEMP: Add console logs to see the values immediately
                console.log('--- Form Submission Check ---');
                console.log('Student value:', studentSelect.value);
                console.log('Subject value:', subjectSelect.value);
                console.log('Teacher ID (from hidden input or select):', teacherId);
                console.log('Is teacherIdInput present?', !!teacherIdInput);
                console.log('Is teacherSelect present?', !!teacherSelect);
                console.log('Is the submit condition met?', !!studentSelect.value && !!subjectSelect.value && !!teacherId);
                console.log('----------------------------');


                // Only submit if student, subject, AND teacher fields have values
              // Only submit if student, subject, AND teacher fields have values
if (studentSelect.value && subjectSelect.value && teacherId) {
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');

    const startDate = new Date(startDateInput?.value ?? '{{ now()->format("Y-m-d") }}');
    const endDate = new Date(endDateInput?.value ?? '{{ now()->format("Y-m-d") }}');

    const dates = [];
    for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
        dates.push(new Date(d));
    }

    Promise.all(dates.map(date => {
        const formData = new FormData(form);
        formData.set('teacher_id', teacherId); // Ensure teacher_id is set
        formData.set('schedule_date', date.toISOString().slice(0, 10)); // Format as yyyy-mm-dd

        return fetch("{{ route('schedules.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        }).then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Server error: ' + response.statusText);
                });
            }
            return response.json();
        });
    }))
    .then(() => {
        location.reload();
    })
    .catch(error => {
        console.error('Error submitting schedules:', error);
        alert('An error occurred during schedule creation: ' + error.message);
        studentSelect.value = '';
        subjectSelect.value = '';
    });
} else {
    console.log('Form not submitted: Missing student, subject, or teacher ID.');
}

            });
        });

        // Handle teacher selection change (This block seems correct for empty rows)
        document.querySelectorAll('.teacher-select').forEach(select => {
            select.addEventListener('change', function() {
                const teacherId = this.value;
                const roomId = this.dataset.roomId;
                
                document.querySelectorAll(`tr .schedule-form input[name="room_id"][value="${roomId}"]`).forEach(input => {
                    const form = input.closest('form');
                    const teacherIdInput = form.querySelector('input[name="teacher_id"]');
                    if (teacherIdInput) {
                        teacherIdInput.value = teacherId;
                    }
                });
            });
        });
    });

    function deleteSchedule(scheduleId) {
        if (confirm('Are you sure you want to delete this schedule?')) {
            fetch(`/schedules/${scheduleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error deleting schedule');
                }
            });
        }
    }

    // // Function to show teacher students (assuming this is defined elsewhere)
    // function showTeacherStudents(teacherId, scheduleDate) {
    //     // Implement logic to show a modal or redirect with teacher's students
    //     alert(`Showing students for Teacher ID: ${teacherId} on Date: ${scheduleDate}`);
    
    // }

    // Function to confirm delete by room and date (assuming this is defined elsewhere)
    function confirmDeleteByRoomAndDate(roomId, scheduleDate) {
        if (confirm('Are you sure you want to delete all schedules for this room on this date?')) {
            fetch(`/schedules/delete-by-room-date`, {
                method: 'POST', // Or DELETE if your route supports it
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ room_id: roomId, schedule_date: scheduleDate })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error deleting schedules by room and date');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during bulk deletion.');
            });
        }
    }
</script>

</x-app-layout>
