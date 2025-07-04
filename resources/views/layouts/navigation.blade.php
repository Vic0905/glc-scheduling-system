<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm font-sans text-black dark:text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
           <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <svg class="h-8 w-auto text-blue-700 dark:text-blue-400 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5 5.754 5 4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-800 dark:text-white tracking-tight">GLC</span>
                </a>
            </div>
            <!-- Desktop Nav Links -->
            <div class="hidden sm:flex sm:items-center space-x-8"> {{-- Increased space-x --}}
                @role('admin')
                    {{-- DASHBOARD --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-layout-dashboard 
                                    {{ request()->routeIs('dashboard') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' }}
                                    group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <rect width="7" height="9" x="3" y="3" rx="1"/>
                                <rect width="7" height="5" x="14" y="3" rx="1"/>
                                <rect width="7" height="9" x="14" y="12" rx="1"/>
                                <rect width="7" height="5" x="3" y="16" rx="1"/>
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                Dashboard
                            </span>
                        </div>
                    </x-nav-link>

                    {{-- STUDENTS --}}
                    <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.index')"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-users-round 
                                    {{ request()->routeIs('students.index') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' }}
                                    group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <path d="M18 21a8 8 0 0 0-16 0" />
                                <circle cx="10" cy="8" r="5" />
                                <path d="M22 21a8 8 0 0 0-16 0" />
                                <path d="M10 2v3" />
                                <path d="M12 7l-2 3-2-3" />
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                Students
                            </span>
                        </div>
                    </x-nav-link>

                    {{-- SUBJECTS --}}
                    <x-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.index')"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-book-open 
                                    {{ request()->routeIs('subjects.index') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' }}
                                    group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                Subjects
                            </span>
                        </div>
                    </x-nav-link>

                    {{-- ROOMS --}}
                    <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-home 
                                    {{ request()->routeIs('rooms.index') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' }}
                                    group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                Rooms
                            </span>
                        </div>
                    </x-nav-link>

                    {{-- TEACHERS --}}
                    <x-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.index')"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-graduation-cap 
                                    {{ request()->routeIs('teachers.index') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' }}
                                    group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                <path d="M6 12v5c3 0 6 2 6 5s3 5 6 5" />
                                <path d="M4 15l-2-2v4" />
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                Teachers
                            </span>
                        </div>
                    </x-nav-link>

                    {{-- SCHEDULES --}}
                    <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar-check 
                                    {{ request()->routeIs('schedules.index') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' }}
                                    group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <path d="M8 2v4" />
                                <path d="M16 2v4" />
                                <path d="M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8" />
                                <path d="M3 10h18" />
                                <path d="m16 20 2 2 4-4" />
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                Schedules
                            </span>
                        </div>
                    </x-nav-link>
                @endrole


                @role('teacher')
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            {{-- Dashboard Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-layout-dashboard {{ request()->routeIs('dashboard') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }} group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <rect width="7" height="9" x="3" y="3" rx="1"/>
                                <rect width="7" height="5" x="14" y="3" rx="1"/>
                                <rect width="7" height="9" x="14" y="12" rx="1"/>
                                <rect width="7" height="5" x="3" y="16" rx="1"/>
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100
                                        transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                Dashboard
                            </span>
                        </div>
                    </x-nav-link>

                    <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')" 
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition-colors duration-200">
                        <div class="flex flex-col items-center group">
                            {{-- My Schedules Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar-check {{ request()->routeIs('schedules.index') ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }} group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <path d="M8 2v4" />
                                <path d="M16 2v4" />
                                <path d="M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8" />
                                <path d="M3 10h18" />
                                <path d="m16 20 2 2 4-4" />
                            </svg>
                            <span class="absolute top-full mt-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100
                                        transition-all duration-300 transform scale-90 group-hover:scale-100 whitespace-nowrap z-10">
                                My Schedules
                            </span>
                        </div>
                    </x-nav-link>
                @endrole
            </div>

           <!-- Header Right Section (User Dropdown + Dark Mode Toggle) -->
            <div class="hidden sm:flex sm:items-center gap-4 pr-4">
                <!-- Dark Mode Toggle -->
                <button id="darkToggle" class="px-3 py-2 border rounded-md text-sm dark:border-gray-400 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    🌓
                </button>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-base font-medium text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md py-2 px-3 transition-all duration-200">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="ml-2 w-4 h-4 text-gray-500 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.292l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.64a.75.75 0 01-1.08 0l-4.25-4.64a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" 
                            class="text-gray-700 dark:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-150">
                            Profile
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" 
                                onclick="event.preventDefault(); this.closest('form').submit();" 
                                class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 hover:text-red-800 dark:hover:text-red-200 transition-colors duration-150">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Icon (Mobile) -->
            <div class="sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden px-4 pb-4 space-y-1 bg-white dark:bg-gray-900">
        @role('admin')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.index')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Students</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.index')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Subjects</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Rooms</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.index')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Teachers</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Schedules</x-responsive-nav-link>
        @endrole

        @role('teacher')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">My Schedules</x-responsive-nav-link>
        @endrole

        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <div class="px-4">
                <div class="font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="block w-full py-2 px-3 rounded-md text-base text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-900 dark:focus:text-white transition-colors duration-150">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full py-2 px-3 rounded-md text-base text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 hover:text-red-800 dark:hover:text-red-200 focus:outline-none focus:bg-red-50 dark:focus:bg-red-900 focus:text-red-800 dark:focus:text-red-200 transition-colors duration-150">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
