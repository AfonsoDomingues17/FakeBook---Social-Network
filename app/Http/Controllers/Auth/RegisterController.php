<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import the Log facade


use Illuminate\View\View;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;

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
    public function register(StoreUserRequest $request)
    {
        Log::info('Incoming request data', $request->all());

        $request->merge([
            'is_public' => $request->is_public === 'public' ? true : false,
        ]);

        $validatedData = $request->validated();

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
            'photo_url' => $photoUrl,
            'gender' => $request->gender,
            'country_id' => $request->country_id,
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        
        return redirect()->route('editprofile', ['user_id' => $user->id])
            ->withSuccess('You have successfully registered & logged in! Please complete your profile.');
    }
}
