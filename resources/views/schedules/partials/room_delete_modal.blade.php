<div id="roomdeleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-25 hidden z-50">
    <div class="bg-gray-900 p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4 text-gray-100">Confirm Deletion</h2>
        <p class="text-gray-100">Are you sure you want to delete all schedules for this teacher and room on this date?</p>
        
        <div class="mt-6 flex justify-end gap-2">
            <button onclick="roomcloseModal()" class="bg-gray-100 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider 
                                           border-2 border-gray-100 hover:border-gray-100 text-gray-900 hover:text-gray-100 rounded-xl transition ease-in duration-100">Cancel</button>

            <button id="confirmDeleteBtn" class="bg-red-500 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider 
                                           border-2 border-red-500 hover:border-red-500 text-white hover:text-red-500 rounded-xl transition ease-in duration-100">Delete</button>
        </div>
    </div>
</div>
