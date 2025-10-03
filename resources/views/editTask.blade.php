
<!-- Add Tasks -->
@extends('layouts.mainlayout')
@section('mainsection')
    <!-- Add Tasks Page -->
    <div class = "bg-[#F5F8FF] flex flex-row w-full h-screen px-[50px] py-[15px] font-Inter">
        <!-- Form Container -->
        <div class= "flex flex-col gap-4 border border-[#C0C2C9] rounded-[12px] w-full h-[85%] overflow-y-auto" >
            <div class="flex flex-row justify-between items-center border-b border-[#C0C2C9] p-4">
                <!-- Inside Container/Top -->
                <h2 class="text-lg font-semibold text-gray-800 font-montserrat underline decoration-[#FF6767] underline-offset-4">Add New Task</h2>
                <a href="viewtasks" class="underline decoration-[#FF6767] underline-offset-4" >Back</a>
            </div>  
            <!-- Below this is the scrollable area -->
            <!-- Form will go here -->

            <form action="{{route('updateTask',$task->id)}}" method="Post" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" value="{{$task->title}}" name="title" id="title" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                </div>
                <!-- description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border border-gray-300 rounded-md bg-[#F5F8FF] shadow-sm p-2 focus:border-[#FF6767] focus:outline-none">{{$task->description}}</textarea>
                </div>
                <!-- Priority -->
                <div class="mb-4">
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" id="priority" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                          <!-- Optional: Add a placeholder that is NOT selected if a value exists -->
                            <option value="" disabled @unless($task->priority) selected @endunless>Select priority</option>

                            <!-- Use a ternary operator to check if the option value matches the task's priority -->
                            <option value="low"    @if($task->priority === 'low')    selected @endif>Low</option>
                            <option value="medium" @if($task->priority === 'medium') selected @endif>Medium</option>
                            <option value="high"   @if($task->priority === 'high')   selected @endif>High</option>
                    </select>
                </div>
                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                        <!-- The default value is pre-selected by checking if the option matches the task's status -->
        
                        <option 
                            value="Pending" 
                            @if($task->status === 'Pending') selected @endif
                        >
                            Pending
                        </option>
                        
                        <option 
                            value="In Progress" 
                            @if($task->status === 'In Progress') selected @endif
                        >
                            In Progress
                        </option>
                        
                        <option 
                            value="Completed" 
                            @if($task->status === 'Completed') selected @endif
                        >
                            Completed
                        </option>
                    </select>
                </div>
                <!-- Category using select-->
                 <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                        <!-- N/A at the top -->
                        <!-- Optional: Add a placeholder option -->
                        <option value="" disabled @unless($task->category_id) selected @endunless>Select a Category</option>
                        
                        <!-- Dynamically list categories and mark the current one as selected -->
                        @foreach($categories as $category)
                            <option 
                                value="{{ $category->id }}"
        
                                @if($category->id === $task->category_id) 
                                    selected 
                                @endif
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                 </div>
                <!-- Due Date -->
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" value = "{{$task->due_date}}" name="due_date" id="due_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                </div>
                <!-- Attachment -->
                <!-- <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment</label>
                    <input type="file" name="attachment" id="attachment" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none"> -->
                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment</label>

                    @if ($task->attachment)
                        <!-- 1. Display the current file path/link -->
                        <p class="mt-1 text-sm text-gray-600 mb-2">
                            Current File: 
                            <a 
                                href="{{ asset('storage/' . $task->attachment) }}" 
                                target="_blank" 
                                class="text-[#FF6767] hover:text-[#e65c5c] font-medium transition duration-150"
                            >
                                View Attachment ({{ pathinfo($task->attachment, PATHINFO_BASENAME) }})
                            </a>
                            <!-- Optional: Add a hidden field or checkbox to allow deletion -->
                            {{-- <input type="checkbox" name="delete_attachment" value="1"> Delete? --}}
                        </p>
                    @endif
                

                    <!-- 2. The input is left empty for the user to upload a NEW file -->
                    <input 
                        type="file" 
                        name="attachment" 
                        id="attachment" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#FF6767] file:text-white hover:file:bg-[#e65c5c] focus:border-[#FF6767] focus:outline-none"
                    >
                    
                    @if ($task->attachment)
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep the current file, or choose a new file to replace it.</p>
                    @endif
                </div>

            
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#FF6767] text-white px-4 py-2 rounded-md hover:bg-[#e65c5c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6767] font-montserrat">
                        Update Task
                    </button>
                </div>
            </form>
        </div >
    </div>
@endsection
<!-- End of Add Tasks Page -->