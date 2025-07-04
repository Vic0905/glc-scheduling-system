<!-- Create Teacher Modal -->
<div id="createTeacherModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="dark:bg-gray-900 bg-white rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-2xl mx-4 transform scale-95 transition-all duration-300">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold dark:text-gray-100 text-gray-800">Add Teacher</h2>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('teachers.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="createName" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Name</label>
                <input type="text" name="name" id="createName" value="{{ old('name') }}" required
                    placeholder="Enter teacher's name"
                    class="w-full px-4 py-2 border dark:bg-gray-900 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nickname -->
            <div>
                <label for="createNickname" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Nickname</label>
                <input type="text" name="nickname" id="createNickname" value="{{ old('nickname') }}" required
                    placeholder="Enter teacher's nickname"
                    class="w-full px-4 py-2 border dark:bg-gray-900 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                @error('nickname')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="createEmail" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="createEmail" value="@gmail.com" required
                    placeholder="Enter teacher's email"
                    class="w-full px-4 py-2 border dark:bg-gray-900 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="createPassword" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="createPassword" value="00000000" required
                    class="w-full px-4 py-2 border dark:bg-gray-900 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="createPasswordConfirmation" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="createPasswordConfirmation" value="00000000" required
                    class="w-full px-4 py-2 border dark:bg-gray-900 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeCreateModal()"
                    class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100"
                >
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                  border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-blue-400 text-gray-100 hover:text-blue-900 dark:text-white dark:hover:text-white rounded-lg transition ease-in duration-100"
                >
                    Add Teacher
                </button>
            </div>
        </form>
    </div>
</div>
