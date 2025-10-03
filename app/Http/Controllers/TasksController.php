<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    // Tasks Function
    public function viewTasks()
    {
        // Fetch tasks of logged in user only
        $tasks = Task::where('user_id', auth()->id())->with('category')->get();
        return view('viewtasks',compact('tasks'));
    }

    // View Tasks Function
    public function viewAddTasks()
    {
        
        $categories = Category::where('user_id', auth()->id())->get();
        return view('viewAddTasks', compact('categories'));
    }
    // Add Tasks Function send data to database

     public function addTasks(Request $request){
        
        $validated= $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:Pending,In Progress,Completed',
            'category' => 'required|exists:category,id',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);
        
        

        $task = new Task();
        $task->user_id = auth()->id(); // Assuming user is authenticated
        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->priority = $validated['priority'];
        $task->status = $validated['status'];
        $task->category_id = $validated['category'];
        $task->due_date = $validated['due_date'];

         // Handle file upload

    if ($request->hasFile('attachment')) {
        if ($validated['attachment']) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $task->attachment = $path;
        }}

        $task->save();

        return redirect()->route('viewtasks')->with('success', 'Task added successfully.');
     }

    //  Edit Task Function
    public function editTask($id)
    {
        // authorize user, check id -> user_id matches auth()->id()
        if (!Task::where('id', $id)->where('user_id', auth()->id())->exists()) {
            abort(403);
        }
        
        // $task = Task::findOrFail($id);
        $task = Task::with('category')->findOrFail($id); 
        $categories = Category::where('user_id', auth()->id())->get();

        return view('editTask', compact('task', 'categories'));
    }
    // Update Task Function
    public function updateTask(Request $request, $id)
    {
        // authorize user, check id -> user_id matches auth()->id()
        // if (!Task::where('id', $id)->where('user_id', auth()->id())->exists()) {
        //     abort(403);
        // }
        
        // dd both request and id to debug
        //  dd($request->all(), $id);

        $validated= $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:Pending,In Progress,Completed',
            'category' => 'required|exists:category,id',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);
        $task = Task::findOrFail($id);

        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->priority = $validated['priority'];
        $task->status = $validated['status'];
        $task->category_id = $validated['category'];
        $task->due_date = $validated['due_date'];
            // Handle file upload
        if ($request->hasFile('attachment')) {
            if ($validated['attachment']) {
                $path = $request->file('attachment')->store('attachments', 'public');
                $task->attachment = $path;
            }}
        $task->save();
        return redirect()->route('viewtasks')->with('success', 'Task updated successfully.');
    }

    // Delete Task Function
    public function deleteTask($id)
    {
        // authorize user, check id -> user_id matches auth()->id()
        if (!Task::where('id', $id)->where('user_id', auth()->id())->exists()) {
            abort(403);
        }
        
    
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('viewtasks')->with('success', 'Task deleted successfully.');
    }

    // Toggle Task Completion Function
    public function toggleTaskCompletion(Request $request,$id)
    {
       
         // 1. Find the task that belongs to the authenticated user and retrieve it.
        $task = Task::where('id', $id)->where('user_id', Auth::id())->first();

        // 2. Authorization check: If the task isn't found (or doesn't belong to the user), abort.
        if (!$task) {
            abort(403, 'You do not have permission to modify this task.');
        }

        // 3. For a 'toggle' endpoint, we simply flip the current status.
        // If the task is 'Completed', set it to 'Pending'. Otherwise, set it to 'Completed'.
        $newStatus = ($task->status === 'Completed') ? 'Pending' : 'Completed';

        // Assuming your Task model uses 'status' as a string field:
        $task->status = $newStatus;
        $task->save();

        // 4. Return a response. If the request expects JSON (typical for AJAX toggle),
        // send a JSON response, otherwise redirect back.
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'task' => $task,
                'message' => 'Task status updated to ' . $task->status,
                'new_status' => $task->status, // Return the new status for the client-side UI update
            ]);
        }

        return back()->with('success', 'Task status successfully updated.');
    }
    
        



}
