<?php

namespace App\Http\Controllers;

use App\Events\FollowRequestDeleted;
use App\Models\User;
use App\Models\Watchlist;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;
use App\Events\FollowRequest;

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
        $blockedUserIds = $user->blockedUsers()->pluck('target_user_id')->merge($user->blockedBy()->pluck('initiator_user_id'));
        $users = User::where('id', '!=', $user->id)
                ->whereNotIn('id', $user->following()->pluck('id'))
                ->whereNotIn('id', $blockedUserIds)
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
    public function getNumberNotifications($user_id){
        $user = User::findOrFail($user_id);
        $n_notifications = $user->notifications()->count();
        return $n_notifications;
    }
    public function createUserbyAdmin(Request $request)
    {
        
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
            
            $photoUrl = $file->store('profile_pictures', 'private'); // Stores in storage/app/public/profile_pictures
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
        

        return redirect()->route('profile', ['user_id' => $user->id])
                     ->with('success', 'User created successfully.');
    }
    public function showProfile($user_id)
{
    // Check if the user_id is a valid integer
    if (!is_numeric($user_id) || (int)$user_id != $user_id) {
        // Set an error message to be passed to the view
        $error_message = 'Invalid user ID provided.';
        $user = null; // Set user to null since we can't find a user
    } else {
        // Proceed with the valid user_id
        $user = User::find($user_id);

        if (!$user) {
            // If the user was not found in the database, set an error message
            $error_message = 'User not found.';
        } else {

            $currentUser = Auth::user();
            $isBlockedBy = false;

            if ($currentUser) {
                $isBlocked = $currentUser->blockedUsers()->where('target_user_id', $user_id)->exists();
                $isBlockedBy = $user->blockedUsers()->where('target_user_id', $currentUser->id)->exists();
            }

            if ($isBlockedBy) {
                // If the current user is blocked by the user, set an error message
                $error_message = 'This user has blocked you.';
                $user = null; // Set user to null to prevent displaying profile information
            } else {
            // No errors, proceed with fetching the user posts and data
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
                'isInWatchlist' => $isInWatchlist,
                'isBlocked' => $isBlocked ?? false,
                'error_message' => $error_message ?? null
            ]);
        }
    }
}

    // If we reached here, it means there was an invalid user_id or no user found
    return view('pages.user', [
        'user' => $user,
        'error_message' => $error_message
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
    public function follow($user_id)
    {
        $user = User::findOrFail($user_id);
        Log::info('User: ' . $user);
        if($user->is_public){
            $follower = Auth::user();
            $follower->following()->attach($user_id, ['typer' => 'FOLLOW']);

            return response()->json(['success' => true, 'message' => 'User followed successfully.']);
        }
        else{
            $notification = Notification::create([
                'content' => 'has sent you a follow request.',
                'user_id_dest' => $user_id,
                'user_id_src' => Auth::id(),
                'typen' => 'FOLLOW_REQUEST',
                'isread' => false,
            ]);
            event(new FollowRequest(Auth::id(),$notification->id));
            Log::info('User is private reuqest sent.');
            return response()->json(['success' => false, 'message' => 'User is private reuqest sent.']);
        }
    }

    public function acceptFollowRequest($user_id){
        $user = User::findOrFail($user_id);
        $follower = Auth::user();
        $user->following()->attach($follower->id, ['typer' => 'FOLLOW']);
        $is_Following = Auth::user()->isFollowing($user_id);
        return response()->json(['success' => true, 'message' => 'User followed successfully.', 'isFollowing' => $is_Following]);

    }
    public function deleteFollowRequest($user_id){
        $notification = Notification::where('user_id_dest', $user_id)
        ->where('user_id_src', Auth::id())
        ->where('typen', 'FOLLOW_REQUEST')
        ->firstOrFail();
        event(new FollowRequestDeleted($notification->id));
        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Request deleted successfully.']);
    }
    public function declineFollowRequest($notification_id){
        $notification = Notification::findOrFail($notification_id);
        $notification->delete();
        return response()->json(['success' => true, 'message' => 'Request declined successfully.']);
    }

    public function unfollow($user_id)
    {
        $user = User::findOrFail($user_id);
        $follower = Auth::user();
        $follower->following()->detach($user_id);

        return response()->json(['success' => true, 'message' => 'User unfollowed successfully.']);
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
            $questions = Question::all();
            return view('pages.admin', ['users' => $users, 'questions' => $questions]);
        }
        return redirect('/')->with('error', 'You do not have admin access.');
    }

    public function addToWatchlist($user_id)
{
    if (Auth::check() && Auth::user()->isAdmin()) {
        $admin = Auth::user();

        if (!$admin->watchlistedUsers()->where('user_id', $user_id)->exists()) {
            $admin->watchlistedUsers()->attach($user_id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'User added to watchlist.']);
    }
    return response()->json(['success' => false, 'message' => 'You do not have admin access.'], 403);
}

public function removeFromWatchlist($user_id)
{
    if (Auth::check() && Auth::user()->isAdmin()) {
        $admin = Auth::user();

        $admin->watchlistedUsers()->detach($user_id);

        return response()->json(['success' => true, 'message' => 'User removed from watchlist.']);
    }
    return response()->json(['success' => false, 'message' => 'You do not have admin access.'], 403);
}

public function blockUser($user_id)
{
    $user = User::findOrFail($user_id);
    $currentUser = Auth::user();
    $currentUser->following()->detach($user_id);
    // Check if the user is already blocked
    if (!$currentUser->blockedUsers()->where('target_user_id', $user_id)->exists()) {
        $currentUser->blockedUsers()->attach($user_id, ['typer' => 'BLOCK']);
    }

    return redirect()->back()->with('success', 'User blocked successfully.');
}

public function unblockUser($user_id)
{
    $user = User::findOrFail($user_id);
    $currentUser = Auth::user();

    // Check if the user is blocked
    if ($currentUser->blockedUsers()->where('target_user_id', $user_id)->exists()) {
        $currentUser->blockedUsers()->detach($user_id);
    }

    return redirect()->back()->with('success', 'User unblocked successfully.');
}
}
