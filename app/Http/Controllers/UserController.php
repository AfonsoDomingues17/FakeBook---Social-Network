<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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

    public function getSuggestedUsers(){
        if(Auth::check()){
            $user = Auth::user();
            return User::where('id', '!=', $user->id)
                ->whereNotIn('id', $user->following()->pluck('id'))
                ->inRandomOrder()
                ->take(5)->get();
        }
        else {
            return User::inRandomOrder()->take(5)->get();
        }
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
        return view('pages.user', ['user'=> $user, 'n_posts' => $n_posts, 'n_followers' => $n_followers, 'n_following' => $n_following, 'posts' => $posts]);
    }

    public function updateProfile(Request $request)
    {   
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
                Rule::unique('users')->ignore($request->id),
            ],
            'age' => 'required|integer|min:13',
            'bio' => 'nullable|string|max:250',
            'is_public' => 'required|boolean',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        Log::info('Validation successful', $validatedData);

        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->age = $request->age;
        $user->bio = $request->bio;
        $user->is_public = $request->is_public;

        if ($request->hasFile('photo_url')) {
            if ($user->photo_url) {
                $oldPhotoPath = str_replace('/storage/', 'public/', $user->photo_url);
                Storage::delete($oldPhotoPath);
            }
    
            $path = $request->file('photo_url')->store('public/profile_pictures');
            $user->photo_url = $path;
        }
        Log::info('Consegui trocar');

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

    public function search(Request $request)
    {
        // Extract the type and query from the query string
        $type = $request->input('type');
        $query = $request->input('query');

        // Initialize an empty collection for results
        $results = collect();

        // Perform the search based on the type
        if ($type === 'users') {
            $results = User::where('name', 'ILIKE', '%' . $query . '%')
                        ->orWhere('email', 'ILIKE', '%' . $query . '%')
                        ->orWhere('username', 'ILIKE', '%' . $query . '%')
                        ->get();
        } else if($type === 'posts') {
            $results = DB::table('post')
            ->whereRaw("tsvectors @@ to_tsquery('english', ?)", [$query])
            ->get();
        }

        // Return the results to a view, you can adjust the view name as needed
        return view('pages.searchpage', compact('results', 'type', 'query'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

}
