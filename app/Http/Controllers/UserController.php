<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function showEditProfileForm($user_id)
    {
        if (!Auth::check()) {
            return redirect('/login');
        } else {
            $user = User::findOrFail($user_id);
            return view('pages.editProfile', ['user'=> $user]);
        }
    }
    public function getUsers(){
        $users = User::take(10)->get();
        return view('pages.homepage', ['users' => $users]);
    }
        
    
    public function getNumberPosts($user_id){
        $user = User::findOrFail($user_id);
        $n_posts = $user->posts()->count();
        return $n_posts;
    }
    public function getNumberFollowers($user_id){
        $user = User::findOrFail($user_id);
        $n_followers = $user->followers()->count();
        return $n_followers;
    }

    public function getNumberFollowing($user_id){
        $user = User::findOrFail($user_id);
        $n_following = $user->following()->count();
        return $n_following;
    }
    public function showProfile($user_id)
    {
        $user = User::findOrFail($user_id);
        $n_posts = $this->getNumberPosts($user_id);
        $n_followers = $this->getNumberFollowers($user_id);
        $n_following = $this->getNumberFollowing($user_id);
        return view('pages.user', ['user'=> $user, 'n_posts' => $n_posts, 'n_followers' => $n_followers, 'n_following' => $n_following]);
    }

    public function updateProfile(Request $request)
    {   
        $request->merge([
            'is_public' => $request->is_public === 'public' ? true : false,
        ]);
        $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:users',
            'age' => 'required|integer|min:13',
            'bio' => 'string|max:250',
            'is_public' => 'required|boolean'
        ]);

        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->age = $request->age;
        $user->bio = $request->bio;
        $user->is_public = $request->is_public;
        $user->save();
        return redirect()->route('profile', ['user_id' => $user->id]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('auth.register');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'age' => 'required|integer|gt:13',
            'bio' => 'nullable|string',
            'photo_url' => 'nullable|string',
            'is_public' => 'required|boolean',
            'typeU' => 'required|string|in:NORMAL,INFLUENCER', 
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return redirect()->route('login')
                     ->with('success', 'You have successfully registered! Please log in.');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('pages.profile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

}
