@props([
    'action',     // e.g., route name
    'placeholder' => 'Search...',
    'name' => 'search',
    'value' => request($name),
])

<div class="flex flex-col items-center w-full">
    <form action="{{ $action }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full max-w-md">
        <div class="relative w-full uppercase font-bold text-gray-800 dark:text-white">
            <input type="text" name="{{ $name }}" id="{{ $name }}"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                class="block w-full px-3 py-2 pl-10 text-gray-800 dark:text-gray-100 dark:bg-gray-700 bg-white border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
            />
            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 18a7 7 0 100-14 7 7 0 000 14zM21 21l-4.35-4.35" />
                </svg>
            </div>
        </div>
    </form>
</div>
