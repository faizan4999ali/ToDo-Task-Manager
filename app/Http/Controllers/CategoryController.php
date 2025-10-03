<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function categories()
    {
       $categories = Category::where('user_id', auth()->id()) // 1. Filter by logged-in user
        ->withCount('tasks') // 2. Get the task count
        ->with(['tasks' => function ($query) { // 3. Eager load tasks (only titles needed)
            $query->select('id', 'category_id', 'title');
        }])
        ->get();

    return view('categories', compact('categories'));
}

// Controller method for deletion (requires a route: Route::delete('/categories/{category}', 'CategoryController@destroy')->name('categories.destroy');)
    public function destroy(Category $category)
    {
        // 1) Ensure user owns the category
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        // 1.5) Prevent deleting the default category itself
        // (choose the same name you will use below)
        if (strtolower($category->name) === 'uncategorized') {
            return redirect()->back()->with('error', 'Cannot delete the default "Uncategorized" category.');
        }

        $userId = auth()->id();

        // 2) Find or create a default category for this user
        $defaultCategory = Category::firstOrCreate(
            ['user_id' => $userId, 'name' => 'Uncategorized'],
            // optional extra attributes:
            ['created_at' => now(), 'updated_at' => now()]
        );

        // 3) Move tasks and delete category inside a transaction
        DB::transaction(function () use ($category, $defaultCategory) {
            // Efficient mass update
            Task::where('category_id', $category->id)
                ->update(['category_id' => $defaultCategory->id]);

            // Now delete the category
            $category->delete();
        });

        return redirect()->route('categories')->with('success', 'Category deleted and tasks moved to "Uncategorized".');
    }
    

    // Store new category
    public function storeCategory(Request $request)
     {
    //     // first verify user is logged in
    //     if (!auth()->check()) {
    //         return redirect()->route('signin')->with('error', 'You must be logged in to create a category.');
    //     }
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Category::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('categories')->with('success', 'Category created successfully!');
    }

    // Edit category function
    public function editCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);

        // Ensure the user owns the category
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $category->name = $request->name;
        $category->save();

        return redirect()->route('categories')->with('success', 'Category updated successfully!');
    }

    // 

   

}
