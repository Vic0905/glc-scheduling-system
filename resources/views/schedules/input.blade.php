<x-app-layout>
    <x-slot name="header">
       <div class="flex items-center w-full relative">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight tracking-tight">
                {{ __('Input ') }} 
            </h2>
            <div class="absolute left-1/2 transform -translate-x-1/2">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight tracking-tight">
                    {{ __('Advance Schedule') }} 
                </h2>
            </div>
        </div>
    </x-slot>

        <div class="flex flex-col md:flex-row md:justify-between md:items-end items-stretch p-4 gap-3 w-full max-w-6xl mx-auto">
            @php
                $startDate = request('start_date') ?? now()->format('Y-m-d');
                $endDate = request('end_date') ?? now()->format('Y-m-d');
            @endphp
            <form action="{{ route('schedules.input') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-end gap-3 w-full md:w-auto">
                <div class="flex flex-col text-sm w-full sm:w-48">
                    <label for="teacher_name" class="text-gray-700 dark:text-gray-200 font-semibold mb-1">Teacher</label>
                    <div class="relative">
                        <input type="text" name="teacher_name" id="teacher_name" value="{{ request('teacher_name') }}"
                            placeholder="Search teacher"
                            class="w-full px-3 py-2 pl-9 text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                            </svg>
                        </div>
                    </div>
                </div>  

                <div class="flex flex-col text-sm">
                    <label for="start_date" class="text-gray-700 dark:text-gray-200 font-semibold mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div class="flex flex-col text-sm">
                    <label for="end_date" class="text-gray-700 dark:text-gray-200 font-semibold mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" />
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

            <div class="flex flex-col justify-end">
                <a href="{{ route('schedules.index') }}"
                    class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                        border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                    Back to Schedules
                </a>
            </div>
        </div>

    @include('schedules.partials.schedule_input')
    @include('components.alerts.success')

    <script>
    function initializeScheduleFormEvents() {
        document.querySelectorAll('.schedule-form select').forEach(select => {
            if (!select.dataset.bound) {
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
        initializeScheduleFormEvents();

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
 