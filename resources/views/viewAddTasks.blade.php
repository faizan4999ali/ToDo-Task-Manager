
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

            <form action="{{route('addTasks')}}" method="Post" enctype="multipart/form-data" class="p-6">
                @csrf
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" name="title" id="title" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                </div>
                <!-- description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border border-gray-300 rounded-md bg-[#F5F8FF] shadow-sm p-2 focus:border-[#FF6767] focus:outline-none"></textarea>
                </div>
                <!-- Priority -->
                <div class="mb-4">
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" id="priority" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                        <option value="" disabled selected>Select priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                        <option value="" disabled selected>Select status</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <!-- Category using select-->
                 <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                        <!-- N/A at the top -->
                        <option value="" disabled selected>Select category</option>
                         <!-- Dynamically fetch categories from database -->

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                    </select>
                 </div>
                <!-- Due Date -->
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" id="due_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                </div>
                <!-- Attachment -->
                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment</label>
                    <input type="file" name="attachment" id="attachment" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-[#F5F8FF] focus:border-[#FF6767] focus:outline-none">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#FF6767] text-white px-4 py-2 rounded-md hover:bg-[#e65c5c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6767] font-montserrat">
                        Save Task
                    </button>
                </div>
            </form>
        </div >
    </div>
@endsection
<!-- End of Add Tasks Page -->