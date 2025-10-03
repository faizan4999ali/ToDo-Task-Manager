<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\TasksController;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\CategoryController;

//  Signup Route
Route::get('/signup', [AuthController::class, 'Signup'])->name('signup');
//  Send Signup data
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Signin Route
Route::get('/login', [AuthController::class, 'Signin'])->name('signin');
// Send Signin data
Route::post('/login', [AuthController::class, 'login'])->name('login');


// Auth Routes using middleware
Route::middleware('auth')->group(function () {

    // Protected routes go here


// Layout Route
Route::get('/', [LayoutController::class, 'Layout'])->name('layout');
// Tasks Route
Route::get('/viewtasks', [TasksController::class, 'viewTasks'])->name('viewtasks');
// Add Tasks Route
Route::get('/viewAddTasks', [TasksController::class, 'viewAddTasks'])->name('viewAddTasks');
// Send Add Tasks data
Route::post('/addTasks', [TasksController::class, 'addTasks'])->name('addTasks');
// Edit Tasks Route
Route::get('/editTask/{task}', [TasksController::class, 'editTask'])->name('editTask');
// Send Edit Tasks data
Route::put('/editTask/{task}', [TasksController::class, 'updateTask'])->name('updateTask');
// Delete Tasks Route
Route::delete('/deleteTask/{task}', [TasksController::class, 'deleteTask'])->name('deleteTask');
// Logout Route
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Categories Route
Route::get('/categories', [CategoryController::class, 'categories'])->name('categories');

// Send Create New Category data
Route::post('/storeCategory', [CategoryController::class, 'storeCategory'])->name('storeCategory');
// Delete Category Route
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Edit Category Route
Route::Put('/editCategory/{categoryId}', [CategoryController::class, 'editCategory'])->name('editCategory');


// toggleTaskCompletion Route
Route::post('/toggleTaskCompletion/{id}', [TasksController::class, 'toggleTaskCompletion'])->name('toggleTaskCompletion');


});