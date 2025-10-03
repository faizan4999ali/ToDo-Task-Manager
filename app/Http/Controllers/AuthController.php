<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Signup Function
    public function Signup()
    {
        return view('Signup');
    }
    // Register Function send data to database
    public function register(Request $request)
    {
        
        try {
            $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        
        $user = new User();
        $user->first_name =$validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();
            
        return redirect()->route('signin')->with('success', 'Registration successful. Please sign in.');

        } catch (\Exception $e) {
        // Show error while debugging
       
        return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        // return back()->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }
    }


    // Signin Function

    public function Signin()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/viewtasks');
        }  
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // redirect with message
        return redirect('/login')->with('success', 'Logged out successfully.');
    }



}
