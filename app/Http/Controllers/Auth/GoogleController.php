<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $user = Socialite::driver('google')->user();

        $existingUser = User::where('google_id', $user->getId())->first();

        if ($existingUser) {
            Auth::login($existingUser);
            return redirect()->route('homepage');
        } else {
            $randomPassword = Str::random(16);
            $username = Str::slug($user->name, '_');

            while (User::where('username', $username)->exists()) {
                $username = Str::slug($user->name, '_') . Str::random(5);
            }

            $newUser = User::create([
                'name' => $user->name,
                'username' => $username,
                'email' => $user->email,
                'google_id' => $user->id,
                'google_token' => $user->token,
                'password' => Hash::make($randomPassword),
                'age' => 99,
                'bio' => '',
                'is_public' => true,
                'is_banned' => false,
                'photo_url' => null,
                'gender' => 'Other',
            ]);

            Auth::login($newUser);
            return redirect()->route('editprofile', ['user_id' => $newUser->id]);
        }
    }
}
