<x-app-layout>
    <x-slot name="header">
         <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight tracking-tight">
            @role('admin'){{ __('Admin Dashboard') }} @elserole('teacher') {{ __('Teacher Dashboard') }} @else {{ __('Dashboard') }} @endrole
        </h2>
    </x-slot> 

    @role('admin')
        <div class="py-10 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <div class="max-w-6xl mx-auto px-4 sm:px-5 lg:px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Students Count Card -->
                    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md cursor-pointer group hover:border-blue-300 dark:hover:border-blue-500 transition-all">
                        <div class="p-5 flex flex-col items-center justify-center text-gray-800 dark:text-gray-200">
                            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-800 group-hover:bg-blue-200 dark:group-hover:bg-blue-600 flex items-center justify-center mb-3 transition-colors duration-300">
                                <svg class="lucide lucide-users-round text-blue-700 dark:text-blue-200 group-hover:text-blue-900 dark:group-hover:text-white transition-colors duration-300" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 21a8 8 0 0 0-16 0" />
                                    <circle cx="10" cy="8" r="5" />
                                    <path d="M22 21a8 8 0 0 0-16 0" />
                                    <path d="M10 2v3" />
                                    <path d="M12 7l-2 3-2-3" />
                                </svg>
                            </div>
                            <h3 class="text-md font-semibold mb-1">Total Students</h3>
                            <p id="studentsCount" class="text-4xl font-extrabold text-blue-500 dark:text-blue-300 animate-fade-in">0</p>
                        </div>
                    </div>

                    <!-- Teachers Count Card -->
                    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md cursor-pointer group hover:border-green-300 dark:hover:border-green-500 transition-all">
                        <div class="p-5 flex flex-col items-center justify-center text-gray-800 dark:text-gray-200">
                            <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-800 group-hover:bg-green-200 dark:group-hover:bg-green-600 flex items-center justify-center mb-3 transition-colors duration-300">
                                <svg class="lucide lucide-graduation-cap text-green-700 dark:text-green-200 group-hover:text-green-900 dark:group-hover:text-white transition-colors duration-300" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                    <path d="M6 12v5c3 0 6 2 6 5s3 5 6 5" />
                                    <path d="M4 15l-2-2v4" />
                                </svg>
                            </div>
                            <h3 class="text-md font-semibold mb-1">Total Teachers</h3>
                            <p id="teachersCount" class="text-4xl font-extrabold text-green-500 dark:text-green-300 animate-fade-in">0</p>
                        </div>
                    </div>

                    <!-- Subjects Count Card -->
                    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md cursor-pointer group hover:border-red-300 dark:hover:border-red-500 transition-all">
                        <div class="p-5 flex flex-col items-center justify-center text-gray-800 dark:text-gray-200">
                            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-800 group-hover:bg-red-200 dark:group-hover:bg-red-600 flex items-center justify-center mb-3 transition-colors duration-300">
                                <svg class="lucide lucide-book-open text-red-700 dark:text-red-200 group-hover:text-red-900 dark:group-hover:text-white transition-colors duration-300" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                </svg>
                            </div>
                            <h3 class="text-md font-semibold mb-1">Total Subjects</h3>
                            <p id="subjectsCount" class="text-4xl font-extrabold text-red-500 dark:text-red-300 animate-fade-in">0</p>
                        </div>
                    </div>

                    <!-- Schedules Count Card -->
                    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md cursor-pointer group hover:border-yellow-300 dark:hover:border-yellow-500 transition-all">
                        <div class="p-5 flex flex-col items-center justify-center text-gray-800 dark:text-gray-200">
                            <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-800 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-600 flex items-center justify-center mb-3 transition-colors duration-300">
                                <svg class="lucide lucide-calendar-check text-yellow-700 dark:text-yellow-200 group-hover:text-yellow-900 dark:group-hover:text-white transition-colors duration-300" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 2v4" />
                                    <path d="M16 2v4" />
                                    <path d="M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8" />
                                    <path d="M3 10h18" />
                                    <path d="m16 20 2 2 4-4" />
                                </svg>
                            </div>
                            <h3 class="text-md font-semibold mb-1">Total Schedules</h3>
                            <p id="schedulesCount" class="text-4xl font-extrabold text-yellow-500 dark:text-yellow-300 animate-fade-in">0</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endrole



    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
    </style>

   <script>
    function easeOutQuad(t) {
        return t * (2 - t);
    }

    function animateCount(elementId, targetNumber, duration = 2000) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1); // Clamp between 0 and 1
            const easedProgress = easeOutQuad(progress);
            const currentValue = Math.floor(easedProgress * targetNumber);

            element.textContent = currentValue;

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                element.textContent = targetNumber; // Final value
            }
        }

        requestAnimationFrame(update);
    }

    document.addEventListener("DOMContentLoaded", function () {
        animateCount("studentsCount", {{ $studentsCount ?? 0 }});
        animateCount("teachersCount", {{ $teachersCount ?? 0 }});
        animateCount("subjectsCount", {{ $subjectsCount ?? 0 }});
        animateCount("schedulesCount", {{ $schedulesCount ?? 0 }});

    });
</script>

</x-app-layout>
