@extends('layouts.mainlayout')

@php
    // --- PHP Helper Functions for Color Classes ---
    function getPriorityColors($priority) {
        $priority = strtolower($priority);
        return match ($priority) {
            'high' => 'bg-red-100 text-red-800 font-bold',
            'medium' => 'bg-yellow-100 text-yellow-800 font-semibold',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    function getStatusColors($status) {
        $status = strtolower($status);
        return match ($status) {
            'completed' => 'bg-green-100 text-green-800 font-bold',
            'in progress' => 'bg-blue-100 text-blue-800 font-semibold',
            'pending', 'not_started' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    // Prepare the tasks data for Alpine.js (including the category relationship)
    $tasksWithCategory = $tasks->load('category');
    
    // Instead of just the first task, prepare all tasks for Alpine.js
    $tasksJson = $tasksWithCategory->map(function($task) {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'priority' => $task->priority,
            'status' => $task->status,
            'attachment' => $task->attachment,
            'due_date' => $task->due_date,
            'created_at' => $task->created_at,
            'category' => $task->category ? [
                'id' => $task->category->id,
                'name' => $task->category->name
            ] : null
        ];
    })->toJson();

    $initialTask = $tasksWithCategory->first() ? json_encode($tasksWithCategory->first()) : 'null';

    // Tailwind Compiler Workaround
    $tailwindClasses = 'hidden bg-red-100 text-red-800 bg-yellow-100 text-yellow-800 bg-green-100 text-green-800 bg-blue-100 text-blue-800 bg-gray-100 text-gray-800 bg-purple-100 text-purple-800';

    $sessionFlashMessage = session('success') ?? session('error') ?? null;
    $flashType = session('success') ? 'success' : (session('error') ? 'error' : 'default');

@endphp

@section('mainsection')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .peer:checked ~ .toggle-thumb {
            transform: translateX(1.5rem); 
        }
        [x-cloak] { display: none !important; }
    </style>

    <div class="{{ $tailwindClasses }}"></div>

    <div class="bg-[#F5F8FF] flex flex-row w-full h-screen px-[50px] py-[15px] font-Inter"
        x-data="{ 
            // Store all tasks for reactivity
            allTasks: {{ $tasksJson }},
            selectedTask: {{ $initialTask }},
            isDeleteModalOpen: false,
            taskToDelete: null,

            // Find task in allTasks array by ID
            findTaskById: function(taskId) {
                return this.allTasks.find(task => task.id === taskId);
            },

            // Update task status in allTasks array
            updateTaskStatus: function(taskId, newStatus) {
                const taskIndex = this.allTasks.findIndex(task => task.id === taskId);
                if (taskIndex !== -1) {
                    this.allTasks[taskIndex].status = newStatus;
                    
                    // If the updated task is currently selected, update selectedTask too
                    if (this.selectedTask && this.selectedTask.id === taskId) {
                        this.selectedTask.status = newStatus;
                    }
                }
            },

            // Toggle Completion with proper status update
            toggleCompletion: function() {
                if (!this.selectedTask) return;
                
                let newStatus;
                if (this.selectedTask.status === 'Completed') {
                    newStatus = 'In Progress';
                } else {
                    newStatus = 'Completed';
                }
                
                // Send AJAX request to update the task status
                fetch('{{ url('/toggleTaskCompletion') }}/' + this.selectedTask.id, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the task status in our reactive data
                        this.updateTaskStatus(this.selectedTask.id, newStatus);
                        
                        this.toastMessage = data.message || 'Task status updated successfully!';
                        this.toastType = 'success';
                        this.showToast = true;
                    } else {
                        this.toastMessage = data.message || 'Failed to update task status.';
                        this.toastType = 'error';
                        this.showToast = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.toastMessage = 'An error occurred. Please try again.';
                    this.toastType = 'error';
                    this.showToast = true;
                });     
            },
        
            // --- TOAST STATE ---
            showToast: {{ $sessionFlashMessage ? 'true' : 'false' }}, 
            toastMessage: '{{ $sessionFlashMessage ?? '' }}',
            toastType: '{{ $flashType }}',

            // Delete confirmation
            confirmDelete: function(task) {
                this.taskToDelete = task;
                this.isDeleteModalOpen = true;
            },
            
            // Reusable JS function to map priority to classes
            getPriorityColorsJs: function(priority) {
                if (!priority) return 'bg-gray-100 text-gray-800';
                const p = priority.toLowerCase();
                if (p === 'high') return 'bg-red-100 text-red-800 font-bold';
                if (p === 'medium') return 'bg-yellow-100 text-yellow-800 font-semibold';
                if (p === 'low') return 'bg-green-100 text-green-800';
                return 'bg-gray-100 text-gray-800';
            },

            // Reusable JS function to map status to classes
            getStatusColorsJs: function(status) {
                if (!status) return 'bg-gray-100 text-gray-800';
                const s = status.toLowerCase();
                if (s === 'completed') return 'bg-green-100 text-green-800 font-bold';
                if (s === 'in progress') return 'bg-blue-100 text-blue-800 font-semibold';
                if (s === 'pending' || s === 'not_started') return 'bg-yellow-100 text-yellow-800';
                return 'bg-gray-100 text-gray-800';
            },

            // Helper function to format date
            formatDate: function(date) {
                if (!date) return 'N/A';
                return new Date(date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
            },

            // Helper function for Toast classes
            getToastClasses: function(type) {
                switch (type) {
                    case 'success':
                        return 'bg-green-500 border-green-700';
                    case 'error':
                        return 'bg-red-500 border-red-700';
                    default:
                        return 'bg-blue-500 border-blue-700';
                }
            }
        }"
    >
        <!-- First container (Task List) -->
        <div class="flex flex-col gap-4 border border-[#C0C2C9] rounded-[12px] w-1/2 h-[85%] overflow-y-auto mr-4">
            <!-- Header (sticky top) -->
            <div class="flex flex-row justify-between items-center border-b border-[#C0C2C9] p-4 sticky top-0 bg-[#F5F8FF] z-10">
                <h2 class="text-lg font-semibold text-gray-800 font-montserrat">My Tasks</h2>
                <a href="{{ route('viewAddTasks') }}" class="cursor-pointer bg-[#FF6767] text-white px-4 py-2 rounded-md hover:bg-[#e65c5c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6767] font-montserrat">
                    Add Task
                </a>
            </div> 
            
            <!-- Task Cards Loop - Now using Alpine.js reactive data -->
            <div class="px-4 pb-4">
                <template x-for="task in allTasks" :key="task.id">
                    <div 
                        @click="selectedTask = task"
                        :class="{ 
                            'shadow-lg border-[#FF6767] border bg-[#E7EAF1]': selectedTask && selectedTask.id === task.id,
                            'border-transparent hover:bg-[#E7EAF1]': !(selectedTask && selectedTask.id === task.id)
                        }"
                        class="relative flex flex-row mb-3 p-4 rounded-[15px] cursor-pointer transition-all duration-150 ease-in-out border group"
                    >
                        {{-- Delete Button on Card --}}
                        <button 
                            @click.stop="confirmDelete(task)" 
                            class="absolute right-1/2 bottom-0 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full opacity-0 group-hover:opacity-100 focus:opacity-100"
                            title="Delete Task"
                        >
                            <i class="fas fa-trash-alt text-lg"></i>
                        </button>

                        <!-- Left side with Title,Desc, priority and status -->
                        <div class="flex flex-col gap-2 w-3/4 pr-4">
                            <h3 class="text-md font-semibold text-gray-800 truncate" x-text="task.title"></h3>
                            <p class="text-sm text-gray-600 line-clamp-2" x-text="task.description"></p>
                            
                            <!-- Tags - Now reactive with Alpine.js -->
                            <div class="flex flex-row gap-2 mt-auto">
                                <span 
                                    class="text-xs px-2 py-1 rounded-full w-fit font-semibold"
                                    :class="getPriorityColorsJs(task.priority)"
                                    x-text="task.priority"
                                ></span>
                                <span 
                                    class="text-xs px-2 py-1 rounded-full w-fit font-semibold"
                                    :class="getStatusColorsJs(task.status)"
                                    x-text="task.status"
                                ></span>
                            </div>
                        </div>
                        
                        <!-- Right side with image and created date -->
                        <div class="flex flex-col justify-between items-center w-1/4 pl-4 border-l border-gray-200">
                            <template x-if="task.attachment">
                                <img :src="'{{ asset('storage') }}/' + task.attachment" alt="Task Attachment" class="w-16 h-16 object-cover rounded-md mb-1">
                            </template>
                            <template x-if="!task.attachment">
                                <div class="w-16 h-16 flex items-center justify-center bg-gray-200 text-gray-500 rounded-md mb-1 text-xs text-center p-1">No File</div>
                            </template>
                            <p class="text-[10px] text-gray-500 text-center">
                                Created: <span x-text="formatDate(task.created_at)"></span>
                            </p>
                        </div>
                    </div> 
                </template>
            </div>
        </div>

        <!-- Second container (Task Details) - Rest remains the same -->
        <div class="flex flex-col gap-4 border border-[#C0C2C9] rounded-[12px] w-1/2 h-[85%] overflow-y-auto ml-4">
            <!-- Header (sticky top) -->
            <div class="flex flex-row justify-between items-center border-b border-[#C0C2C9] p-4 sticky top-0 bg-[#F5F8FF] z-10">
                <h2 class="text-lg font-semibold text-gray-800 font-montserrat">Task Details</h2>
                
                <!-- Actions shown only if a task is selected -->
                <div x-show="selectedTask" class="flex space-x-4">
                    <a :href="'{{ url('/editTask') }}/' + selectedTask.id" 
                        class="underline decoration-[#FF6767] underline-offset-4 font-semibold" 
                    >
                        Edit
                    </a>
                    <button 
                        @click.prevent="confirmDelete(selectedTask)" 
                        class="text-red-500 hover:text-red-700 underline decoration-red-500 underline-offset-4 font-semibold transition"
                    >
                        Delete
                    </button>
                </div>
            </div> 

            <!-- Conditional Display Area -->
            <template x-if="selectedTask">
                <div class="p-4 space-y-6">
                    <!-- Title & Description -->
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900" x-text="selectedTask.title"></h1>
                        <p class="text-gray-700 mt-2" x-text="selectedTask.description"></p>
                    </div>

                    <!-- Attachment Image (Full Size) -->
                    <div x-show="selectedTask.attachment">
                        <h3 class="text-md font-bold text-gray-800 mb-2 border-b pb-1">Attachment</h3>
                        <img :src="'{{ asset('storage') }}/' + selectedTask.attachment" alt="Task Attachment" class="w-full max-h-96 object-contain rounded-lg border border-gray-300">
                    </div>

                    <!-- Metadata (Tags) -->
                    <div class="space-y-4">
                        <div class="border-b flex flex-row justify-between items-center">
                            <h3 class="text-md font-bold text-gray-800 pb-1">Task Metadata</h3>
                            
                            <!-- Toggle button structure -->
                            <div class="flex flex-col items-center ">
                            <p class="text-sm text-gray-800 pb-1 " >Mark as Completed</p>
                            <label for="task-toggle-101" class="relative inline-flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    id="task-toggle-101" 
                                    :checked="selectedTask.status === 'Completed'" 
                                    @change="toggleCompletion()" 
                                    class="sr-only peer"
                                >
                                <div 
                                    :class="{ 
                                        'bg-green-500': selectedTask.status === 'Completed', 
                                        'bg-gray-300': selectedTask.status !== 'Completed' 
                                    }"
                                    class="w-12 h-6 rounded-full mb-3 transition-colors duration-300 shadow-inner peer-focus:ring-2 peer-focus:ring-offset-2 peer-focus:ring-green-500"
                                ></div>
                                <div class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full shadow-md transition-transform duration-300 toggle-thumb"></div>
                            </label>
                            </div>
                        </div>
                        
                        <!-- Priority, Status, Category, Due Date -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Priority -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Priority</p>
                                <span class="text-sm px-3 py-1 rounded-full w-fit inline-block mt-1"
                                    :class="getPriorityColorsJs(selectedTask.priority)"
                                    x-text="selectedTask.priority">
                                </span>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <span class="text-sm px-3 py-1 rounded-full w-fit inline-block mt-1"
                                    :class="getStatusColorsJs(selectedTask.status)"
                                    x-text="selectedTask.status">
                                </span>
                            </div>

                            <!-- Category -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Category</p>
                                <span class="text-sm bg-purple-100 text-purple-800 px-3 py-1 rounded-full w-fit inline-block mt-1 font-semibold" 
                                    x-text="selectedTask.category ? selectedTask.category.name : 'N/A'">
                                </span>
                            </div>

                            <!-- Due Date -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Due Date</p>
                                <span class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded-full w-fit inline-block mt-1 font-semibold"
                                    x-text="selectedTask.due_date ? formatDate(selectedTask.due_date) : 'N/A'">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Placeholder if no task is selected or tasks list is empty -->
            <template x-if="!selectedTask">
                <div class="flex items-center justify-center h-full text-gray-500 text-lg">
                    <template x-if="allTasks.length === 0">
                        <p>No tasks found. Click "Add Task" to get started!</p>
                    </template>
                    <template x-if="allTasks.length > 0">
                        <p>Click a task on the left to view details.</p>
                    </template>
                </div>
            </template>
        </div>
        
        <!-- Delete Modal and Toast components remain the same -->
        <div 
            x-show="isDeleteModalOpen" 
            x-cloak
            class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 transition-opacity"
            @click.away="isDeleteModalOpen = false"
        >
            <div class="bg-white p-8 rounded-xl shadow-2xl max-w-lg w-full transform transition-all" @click.stop>
                <h2 class="text-2xl font-montserrat font-bold mb-4 text-red-600">Confirm Deletion</h2>
                <p class="text-gray-700 mb-6">
                    Are you sure you want to delete the task 
                    <span class="font-semibold" x-text="taskToDelete ? taskToDelete.title : 'this task'"></span>? 
                    This action cannot be undone.
                </p>
                <form method="POST" :action="'{{ url('/deleteTask') }}/' + (taskToDelete ? taskToDelete.id : '')">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-4 mt-6">
                        <button 
                            type="button" 
                            @click="isDeleteModalOpen = false" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                        >
                            Yes, Delete Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div 
            x-show="showToast"
            x-cloak
            class="fixed top-6 right-6 p-4 rounded-lg text-white shadow-xl z-[60]"
            :class="getToastClasses(toastType)"
            x-init="setTimeout(() => { showToast = false }, 5000)"
        >
            <div class="flex items-center justify-between">
                <p class="font-semibold" x-text="toastMessage"></p>
                <button @click="showToast = false" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
@endsection