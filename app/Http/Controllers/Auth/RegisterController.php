<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import the Log facade


use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        Log::info('Incoming request data', $request->all());

        $request->merge([
            'is_public' => $request->is_public === 'public' ? true : false,
        ]);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'age' => 'required|integer|min:13',
            'bio' => 'nullable|string|max:250',
            'is_public' => 'required|boolean',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        Log::info('Validation successful', $validatedData);

        
        $photoUrl = null;
        if ($request->hasFile('photo_url')) {
            
            $file = $request->file('photo_url');
            
            $photoUrl = $file->store('profile_pictures', 'private'); 
        }
        
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'bio' => $request->bio,
            'is_public' => $request->is_public,
            'photo_url' => $photoUrl
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        
        return redirect()->route('profile', ['user_id' => $user->id])
            ->withSuccess('You have successfully registered & logged in!');
    }
}
