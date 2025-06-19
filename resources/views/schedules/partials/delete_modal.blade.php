<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="group select-none w-[250px] flex flex-col p-4 relative items-center justify-center bg-gray-800 border border-gray-800 shadow-lg rounded-2xl">
        <div class="text-center p-3 flex-auto justify-center">
            <svg fill="currentColor" viewBox="0 0 20 20" class="group-hover:animate-bounce w-12 h-12 flex items-center text-gray-600 fill-red-500 mx-auto" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
            </svg>
            <h2 class="text-xl font-bold py-4 text-gray-200">Are you sure?</h2>
            <p class="font-bold text-sm text-gray-500 px-2">
                Do you really want to continue? This process cannot be undone.
            </p>
        </div>
        <div class="p-2 mt-2 text-center flex justify-center items-center space-x-4">
            <button onclick="closeModal()" class="bg-gray-100 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider 
                                           border-2 border-gray-100 hover:border-gray-100 text-gray-900 hover:text-gray-100 rounded-xl transition ease-in duration-150">
                Cancel
            </button>
            <form id="deleteForm" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider 
                                           border-2 border-red-500 hover:border-red-500 text-white hover:text-red-500 rounded-xl transition ease-in duration-150">
                    Confirm
                </button>
            </form>
        </div>
    </div>
</div>
