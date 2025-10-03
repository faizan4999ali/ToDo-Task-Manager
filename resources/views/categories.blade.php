@extends('layouts.mainlayout')

@section('mainsection')
{{-- Load Font Awesome (for trash icon) and Alpine.js for interactivity --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div 
    x-data="{ 
        //  LIVE DATA INJECTION: This array is populated by the $categories variable passed from your Laravel controller.
        categories: {{ json_encode($categories) }}, 
        
        selectedCategory: null,
        isDeleteModalOpen: false,
        categoryToDelete: null,

        isCreateModalOpen: false,

         // --- NEW EDIT STATE PROPERTIES ---
        isEditModalOpen: false,
        categoryToEdit: { id: null, name: '' }, 

        selectCategory(category) {
            this.selectedCategory = category;
        },

        confirmDelete(category) {
            this.categoryToDelete = category;
            this.isDeleteModalOpen = true;
        },
        
        getDeleteRoute(id) {
            // Update this to match your actual Laravel delete route structure
            return `/categories/${id}`; 
        },
         
        // --- NEW EDIT FUNCTIONS ---
        openEditModal(category) {
            this.categoryToEdit.id = category.id;
            this.categoryToEdit.name = category.name; 
            this.isEditModalOpen = true;
        },
        closeEditModal() {
            this.isEditModalOpen = false;
            this.categoryToEdit = { id: null, name: '' };
        },
        getUpdateRoute(id) {
            // Laravel update route for PUT/PATCH requests
            return `/editCategory/${id}`; 
        },

        // --- NEW PAGINATION STATE & LOGIC ---
        perPage: 5, // Number of categories to display per page
        currentPage: 1,

        get paginatedCategories() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            // Return only the slice of categories for the current page
            return this.categories.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.categories.length / this.perPage);
        },
        get isFirstPage() {
            return this.currentPage === 1;
        },
        get isLastPage() {
            return this.currentPage === this.totalPages;
        },
        
        nextPage() {
            if (!this.isLastPage) this.currentPage++;
            // Try to select the first category on the new page
            this.selectedCategory = this.paginatedCategories[0] || null;
        },
        prevPage() {
            if (!this.isFirstPage) this.currentPage--;
            // Try to select the first category on the new page
            this.selectedCategory = this.paginatedCategories[0] || null;
        },
        // ------------------------------------
    }"
    x-init="if (categories.length > 0) selectedCategory = categories[0]"
    class="bg-gray-50 flex h-screen p-10 font-Inter"
>

    {{-- --- Left Container: Category Cards --- --}}
    <div class="flex flex-col w-1/2 pr-8 pb-12">
        <div class="flex flex-row justify-between items-center mb-6 border-b border-gray-200 pb-4">
            <h1 class="text-lg font-semibold text-gray-800 font-montserrat">Task Categories</h1>
            <button @click="isCreateModalOpen = true" class="text-sm cursor-pointer bg-[#FF6767] text-white px-4 py-2 rounded-md hover:bg-[#e65c5c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6767] font-montserrat">
                + Create New Category
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 max-h-[calc(100vh-250px)] overflow-y-auto">
            
            <template x-for="category in paginatedCategories" :key="category.id">
                <div 
                    @click="selectCategory(category)" 
                    :class="{
                        <!-- active state -->
                        'border border-[#FF6767] bg-[#E7EAF1] shadow-lg': selectedCategory && selectedCategory.id === category.id, 
                        <!-- inactive state -->
                        'border-gray-200 hover:bg-gray-100': !selectedCategory || selectedCategory.id !== category.id
                    }"
                    class="bg-[#F5F8FF] p-5 rounded-xl border transition duration-150 ease-in-out cursor-pointer relative group"
                >
                    {{-- Delete Button (Dustbin) --}}
                    <button 
                        @click.stop="confirmDelete(category)" 
                        class="absolute bottom-2 right-4 text-gray-300 hover:text-red-500 transition-colors p-1 rounded-full opacity-0 group-hover:opacity-100 focus:opacity-100"
                        title="Delete Category"
                    >
                        <i class="fas fa-trash-alt text-base"></i>
                    </button>

                     <button 
                        @click.stop="openEditModal(category)" 
                        class="absolute bottom-2 right-10 text-gray-400 hover:text-blue-500 transition-colors p-1 rounded-full opacity-0 group-hover:opacity-100 focus:opacity-100 z-10"
                        title="Edit Category"
                    >
                        <i class="fas fa-edit text-base"></i>
                    </button>

                    <div class="flex flex-row justify-between items-start mb-3">
                        <h2 class="text-xl font-inter font-semibold text-gray-800" x-text="category.name"></h2>
                        
                        <span class="bg-[#FF6767] text-white px-3 py-1 rounded-full text-sm font-bold shadow-sm" x-text="category.tasks_count"></span>
                    </div>
                    <p class="text-gray-500 text-sm font-inter">
                        <span x-text="category.tasks_count"></span> tasks currently belong to this category.
                    </p>
                </div>
            </template>

        </div>

        <!-- Pagination Controls -->
        <div x-show="totalPages > 1" class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
            
            {{-- Page Info --}}
            <p class="text-sm text-gray-600 font-inter">
                Showing page <span class="font-bold" x-text="currentPage"></span> of <span class="font-bold" x-text="totalPages"></span>
            </p>

            {{-- Navigation Buttons --}}
            <div class="flex space-x-3">
                <button 
                    @click="prevPage"
                    :disabled="isFirstPage"
                    :class="{
                        'opacity-50 cursor-not-allowed': isFirstPage, 
                        'hover:bg-gray-100 border-gray-300 text-gray-700': !isFirstPage
                    }"
                    class="px-4 py-2 text-sm font-medium bg-white border rounded-lg transition"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Previous
                </button>
                <button 
                    @click="nextPage"
                    :disabled="isLastPage"
                    :class="{
                        'opacity-50 cursor-not-allowed': isLastPage, 
                        'hover:bg-gray-100 border-gray-300 text-gray-700': !isLastPage
                    }"
                    class="px-4 py-2 text-sm font-medium bg-white border rounded-lg transition"
                >
                    Next <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
        {{-- End Pagination Controls --}}


    </div>

    {{-- --- Right Container: Task Details --- --}}
    <div class="flex flex-col w-1/2 pl-8 border-l border-gray-200">
        <h2 class="text-lg font-montserrat font-bold text-gray-800 mb-6 border-b border-gray-200 pb-4">Task Titles</h2>

        <div x-show="!selectedCategory" class="p-10 text-center text-gray-500 bg-white rounded-xl h-full flex items-center justify-center shadow-md">
            <p class="text-xl">Click on a category to see the tasks within it.</p>
        </div>

        <div x-show="selectedCategory" x-cloak class="flex flex-col bg-[#F5F8FF] p-6 rounded-xl shadow-lg h-full">
            <h3 class="text-lg font-montserrat font-bold mb-4 border-b pb-2 text-gray-800" x-text="selectedCategory ? selectedCategory.name : ''"></h3>
            
            <div x-show="selectedCategory && selectedCategory.tasks && selectedCategory.tasks.length > 0" class="space-y-3 overflow-y-auto">
                <template x-for="task in selectedCategory.tasks" :key="task.id">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border-l-4 border-[#FF6767] shadow-sm">
                        <span class="text-md font-inter font-medium text-gray-700" x-text="task.title"></span>
                        <a href="#" class="text-[#FF6767] hover:text-[#FF6767] transition"><i class="fas fa-arrow-right"></i></a>
                    </div>
                </template>
            </div>

            <div x-show="selectedCategory && (!selectedCategory.tasks || selectedCategory.tasks.length === 0)" class="text-center p-8 text-gray-500 bg-gray-50 rounded-lg">
                <i class="fas fa-box-open text-4xl mb-3 text-gray-400"></i>
                <p class="font-semibold">No active tasks found in this category.</p>
            </div>
        </div>
    </div>


    {{-- --- Delete Confirmation Modal --- --}}
    <div 
        x-show="isDeleteModalOpen" 
        x-cloak
        class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 transition-opacity"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.away="isDeleteModalOpen = false"
    >
        <div class="bg-white p-8 rounded-xl shadow-2xl max-w-lg w-full transform transition-all" @click.stop>
            <h3 class="text-2xl font-bold text-red-600 mb-4 flex items-center"><i class="fas fa-exclamation-triangle mr-3"></i> Confirm Deletion</h3>
            <p class="text-gray-700 mb-6">
                Are you absolutely sure you want to delete the category **<span class="font-bold" x-text="categoryToDelete ? categoryToDelete.name : ''"></span>**? 
                This action is permanent and cannot be undone. It currently contains **<span class="font-bold" x-text="categoryToDelete ? categoryToDelete.tasks_count : 0"></span>** tasks.
            </p>

            <div class="flex justify-end space-x-4">
                <button 
                    @click="isDeleteModalOpen = false" 
                    class="px-5 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-150 font-medium"
                >
                    Cancel
                </button>
                
                {{-- Form to handle the DELETE request --}}
                <form 
                    x-bind:action="getDeleteRoute(categoryToDelete ? categoryToDelete.id : 0)" 
                    method="POST" 
                    class="inline"
                >
                    @csrf
                    @method('DELETE') {{-- Required for Laravel DELETE method --}}
                    <button 
                        type="submit" 
                        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150 font-medium"
                    >
                        Yes, Delete Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- --- Create Category Modal --- --}}
<div 
    x-show="isCreateModalOpen" 
    x-cloak
    class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 transition-opacity"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click.away="isCreateModalOpen = false"
>
    <div class="bg-white p-8 rounded-xl shadow-2xl max-w-lg w-full transform transition-all" @click.stop>
        <h2 class="text-2xl font-montserrat font-bold mb-6 text-gray-800">Create New Category</h2>
        
        <form method="POST" action="{{ route('storeCategory') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Category Name:</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-[#FF6767] focus:border-transparent"
                    placeholder="Enter category name"
                >
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button 
                    type="button" 
                    @click="isCreateModalOpen = false" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-[#FF6767] text-white rounded-lg hover:bg-[#e65c5c] transition"
                >
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

{{-- --- NEW: Edit Category Modal --- --}}
<div 
    x-show="isEditModalOpen" 
    x-cloak
    class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 transition-opacity"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click.away="closeEditModal"
>
    <div class="bg-white p-8 rounded-xl shadow-2xl max-w-lg w-full transform transition-all" @click.stop>
        <h2 class="text-2xl font-montserrat font-bold mb-6 text-gray-800">Edit Category</h2>
        
        <!-- Action is dynamically set to /categories/{id} for the PUT request -->
        <form method="POST" :action="getUpdateRoute(categoryToEdit.id)">
            @csrf
            @method('PUT') {{-- Required for Laravel Update method --}}
            
            <div class="mb-4">
                <label for="edit_name" class="block text-gray-700 font-medium mb-2">Category Name:</label>
                <input 
                    type="text" 
                    id="edit_name" 
                    name="name" 
                    required 
                    x-model="categoryToEdit.name" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-[#FF6767] focus:border-transparent"
                    placeholder="Enter new category name"
                >
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button 
                    type="button" 
                    @click="closeEditModal" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-montserrat font-semibold"
                >
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

    {{-- --- End of Modals --- --}}


</div>

@endsection
