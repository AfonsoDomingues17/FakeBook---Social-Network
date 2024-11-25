<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function showEditProfileForm($user_id)
    {
        
            $user = User::findOrFail($user_id);
            $this->authorize('update', $user);
            return view('pages.editProfile', ['user'=> $user]);
        
    }
    public function getPhoto($user_id)
{   
    Log::info(message: 'Entrou no getPhoto');
    $user = User::findOrFail($user_id);
    Log::info($user->photo_url);
    if ($user->photo_url) {
        Log::info(message: 'phto_url exists');

        $path = storage_path('app/private/' . $user->photo_url);
        Log::info($path);

        if (!Storage::disk('private')->exists($user->photo_url)) {
            abort(404);
        }

        $file = Storage::disk('private')->get($user->photo_url);
        $type = Storage::disk('private')->mimeType($user->photo_url);

        return Response::make($file, 200)->header("Content-Type", $type);
    } else {
        $defaultPath = storage_path('app/private/profile_pictures/default-profile.png');
        Log::info($defaultPath);
        if (!file_exists($defaultPath)) {
            abort(404);
        }

        $file = file_get_contents($defaultPath);
        $type = mime_content_type($defaultPath);
        return response($file, 200)->header("Content-Type", $type);
    }
}
    public function getUsers(){
        $users = User::take(10)->get();
        return view('pages.homepage', ['users' => $users]);
    }

    public function getSuggestedUsers()
{
    $suggestedUsers = [];
    if (Auth::check()) {
        $user = Auth::user();
        $users = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $user->following()->pluck('id'))
            ->where('typeu', '!=', 'ADMIN')
            ->inRandomOrder()
            ->take(5)
            ->get();

        foreach ($users as $suggestedUser) {
            $isInWatchlist = Watchlist::where('admin_id', $user->id)->where('user_id', $suggestedUser->id)->exists();
            $suggestedUser->isInWatchlist = $isInWatchlist;
            $suggestedUsers[] = $suggestedUser;
        }
    } else {
        $users = User::where('typeu', '!=', 'ADMIN')
            ->inRandomOrder()
            ->take(5)
            ->get();

        foreach ($users as $suggestedUser) {
            $suggestedUser->isInWatchlist = false;
            $suggestedUsers[] = $suggestedUser;
        }
    }

    return $suggestedUsers;
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
    $posts = $user->posts()->orderBy('datecreation', 'desc')->get();

    $isInWatchlist = false;
    if (Auth::check() && Auth::user()->isAdmin()) {
        $isInWatchlist = Auth::user()->watchlistedUsers()->where('user_id', $user_id)->exists();
    }

    return view('pages.user', [
        'user' => $user,
        'n_posts' => $n_posts,
        'n_followers' => $n_followers,
        'n_following' => $n_following,
        'posts' => $posts,
        'isInWatchlist' => $isInWatchlist
    ]);
}

    public function updateProfile(Request $request, $user_id)
    {   
        $user = User::findOrFail($user_id);

        $this->authorize('update', $user);

        Log::info('Incoming request data', $request->all());
        $request->merge([
            'is_public' => $request->is_public === 'public' ? true : false,
        ]);
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'username' => [
                'required',
                'string',
                'max:250',
                Rule::unique('users')->ignore($user_id),
            ],
            'age' => 'required|integer|min:13',
            'bio' => 'nullable|string|max:250',
            'is_public' => 'required|boolean',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        Log::info('Validation successful', $validatedData);

        
        $user->name = $request->name;
        $user->username = $request->username;
        $user->age = $request->age;
        $user->bio = $request->bio;
        $user->is_public = $request->is_public;

        if ($request->hasFile('photo_url')) {
            if ($user->photo_url) {
                $oldPhotoPath = str_replace('private/','', $user->photo_url);
                Log::info('Old photo path: ' . $oldPhotoPath);
                Storage::disk('private')->delete($oldPhotoPath);
            }
    
            $path = $request->file('photo_url')->store('profile_pictures','private');
            Log::info('Path: ' . $path);
            $user->photo_url = $path;
        }
        Log::info('user: ' . $user->photo_url);

        $user->save();
        return redirect()->route('profile', ['user_id' => $user_id]);
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
            'age' => 'required|integer|min:13',
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
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $this->authorize('delete', $user);

        // Delete associated posts and their media files
        foreach ($user->posts as $post) {
            foreach ($post->media as $media) {
                Storage::delete($media->photo_url); // Delete the file from storage
                $media->delete(); // Delete the media record
            }
            $post->delete();
        }

        // Delete profile picture
        if ($user->photo_url) {
            $photoPath = str_replace('private/', '', $user->photo_url);
            Storage::disk('private')->delete($photoPath);
        }
        
        // Remove user from all watchlists
        $user->watchlist()->delete(); // As admin
        $user->watchedBy()->detach(); // As watched user
        // Delete the user
        $user->delete();

        return redirect()->route('homepage')->with('success', 'Account deleted successfully.');
    }
    public function adminPage()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $admin = Auth::user();
            $users = $admin->watchlistedUsers()->get()->map(function ($user) {
                $user->isInWatchlist = true; // Since these users are in the watchlist
                return $user;
            });
            return view('pages.admin', ['users' => $users]);
        }
        return redirect('/')->with('error', 'You do not have admin access.');
    }

    

    public function addToWatchlist(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $user_id = $request->input('user_id');
            $admin = Auth::user();

            if (!$admin->watchlistedUsers()->where('user_id', $user_id)->exists()) {
                $admin->watchlistedUsers()->attach($user_id);
            }

            return redirect()->back()->with('success', 'User added to watchlist.');
        }
        return redirect('/')->with('error', 'You do not have admin access.');
    }

    public function removeFromWatchlist(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $user_id = $request->input('user_id');
            $admin = Auth::user();

            $admin->watchlistedUsers()->detach($user_id);

            return redirect()->back()->with('success', 'User removed from watchlist.');
        }
        return redirect('/')->with('error', 'You do not have admin access.');
    }
}
