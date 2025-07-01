<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Input Schedule') }}
        
        </h2>
    </x-slot>

    <div class="flex flex-col md:flex-row md:justify-between items-center p-4 gap-3 w-full max-w-6xl mx-auto">

    @php
        $startDate = request('start_date') ?? now()->format('Y-m-d');
        $endDate = request('end_date') ?? now()->format('Y-m-d');
    @endphp

    <form action="{{ route('schedules.input') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full md:w-auto">
        
        {{-- Teacher Search --}}
        <div class="flex flex-col text-sm w-full sm:w-48">
            <label for="teacher_name" class="text-gray-700 font-semibold mb-1">Teacher</label>
            <div class="relative">
                <input type="text" name="teacher_name" id="teacher_name" value="{{ request('teacher_name') }}"
                    placeholder="Search teacher"
                    class="w-full px-3 py-2 pl-9 text-sm text-gray-800 bg-white border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                />
                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Start Date --}}
        <div class="flex flex-col text-sm">
            <label for="start_date" class="text-gray-700 font-semibold mb-1">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        {{-- End Date --}}
        <div class="flex flex-col text-sm">
            <label for="end_date" class="text-gray-700 font-semibold mb-1">End Date</label>
            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        {{-- Submit Button --}}
        <div class="flex flex-col justify-end">
            <label class="invisible">Filter</label>
            <button type="submit"
                class="bg-gray-900 hover:bg-transparent px-4 py-2 text-xs shadow-sm hover:shadow-lg font-medium tracking-wider
            border-2 border-gray-200 hover:border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition ease-in duration-100">
                Filter
            </button>
        </div>
    </form>

    {{-- Back Button --}}
    <div class="mt-3 md:mt-0">
        <a href="{{ route('schedules.index') }}"
            class="bg-gray-900 hover:bg-transparent px-4 py-2 text-xs shadow-sm hover:shadow-lg font-medium tracking-wider
            border-2 border-gray-200 hover:border-gray-200 text-gray-100 hover:text-gray-900 rounded-lg transition ease-in duration-100">
            Back to Schedules
        </a>
    </div>
</div>

@include('schedules.partials.schedule_input')


<script>
    function initializeScheduleFormEvents() {
        document.querySelectorAll('.schedule-form select').forEach(select => {
            if (!select.dataset.bound) { // Prevent rebinding
                select.dataset.bound = true;

                select.addEventListener('change', function () {
                    const form = this.closest('form');
                    const studentSelect = form.querySelector('select[name="student_id"]');
                    const subjectSelect = form.querySelector('select[name="subject_id"]');

                    const row = this.closest('tr');
                    const teacherSelect = row?.querySelector('.teacher-select');
                    const teacherIdInput = form.querySelector('input[name="teacher_id"]');
                    let teacherId = teacherIdInput?.value || teacherSelect?.value;

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
                            formData.set('teacher_id', teacherId);
                            formData.set('schedule_date', date.toISOString().slice(0, 10));

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
                                        throw new Error(errorData.message || 'Server error');
                                    });
                                }
                                return response.json();
                            });
                        }))
                        .then(() => location.reload())
                        .catch(error => {
                            console.error('Error submitting schedules:', error);
                            alert('An error occurred: ' + error.message);
                            studentSelect.value = '';
                            subjectSelect.value = '';
                        });
                    }
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initializeScheduleFormEvents(); // Initial binding

        // Teacher dropdown change handler for empty rows
        document.querySelectorAll('.teacher-select').forEach(select => {
            select.addEventListener('change', function () {
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

    function clearSchedule(scheduleId, event) {
        event.preventDefault();

        if (!confirm("Clear this schedule to make room for a new one?")) return;

        fetch(`/schedules/${scheduleId}/clear`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Schedule successfully cleared!');

                const button = event.target;
                const cell = button.closest('td');

                // Replace the <td> content with the form layout
                cell.innerHTML = `
                    <form class="schedule-form" data-room-id="${data.room_id}" data-time-slot="${data.time_slot}" data-slot-key="${data.slot_key}">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <input type="hidden" name="room_id" value="${data.room_id}">
                        <input type="hidden" name="schedule_time" value="${data.time_slot}">
                        <input type="hidden" name="${data.slot_key}" value="1">
                        <input type="hidden" name="start_date" value="${data.start_date}">
                        <input type="hidden" name="end_date" value="${data.end_date}">
                        <input type="hidden" name="teacher_id" value=""> 

                        <select name="student_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-1" required>
                            <option value="" selected>Select Student</option>
                            ${data.students.map(student => `<option value="${student.id}">${student.name}</option>`).join('')}
                        </select>

                        <select name="subject_id" class="block w-full text-xs py-1 px-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="" selected>Select Subject</option>
                            ${data.subjects.map(subject => `<option value="${subject.id}">${subject.subjectname}</option>`).join('')}
                        </select>
                    </form>
                `;

                initializeScheduleFormEvents(); 
            } else {
                alert('Failed to clear schedule.');
            }
        })
        .catch(error => {
            console.error('Clear failed:', error);
            alert('An error occurred while clearing the schedule.');
        });
    }


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

    function confirmDeleteByRoomAndDate(roomId, scheduleDate) {
        if (confirm('Are you sure you want to delete all schedules for this room on this date?')) {
            fetch(`/schedules/delete-by-room-date`, {
                method: 'POST',
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
