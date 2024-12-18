<?php

namespace App\Http\Controllers;

use App\Events\FollowRequestDeleted;
use App\Models\User;
use App\Models\Watchlist;
use App\Models\Question;
use App\Http\Requests\CreateUserByAdminRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\BanUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;
use App\Events\FollowRequest;
use App\Models\Banlist;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Country;

class UserController extends Controller
{
    public function showEditProfileForm($user_id)
    {
        
            $user = User::findOrFail($user_id);
            $this->authorize('update', $user);

    // Pass the user and countries to the view
        return view('pages.editProfile', [
            'user' => $user,
        ]);
    }

    public function getPhoto($user_id)
{   
    $user = User::findOrFail($user_id);
    if ($user->photo_url) {

        $path = storage_path('app/private/' . $user->photo_url);
        Log::info($path);

            if (!Storage::disk('private')->exists($user->photo_url)) {
                abort(404);
            }
            $file = Storage::disk('private')->get($user->photo_url);
            $type = Storage::disk('private')->mimeType($user->photo_url);

        return Response::make($file, 200)->header("Content-Type", $type);
    } else {
        $defaultPath = storage_path('app/private/profile_pictures/DEFAULT_USER.png');
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

    public function createUserbyAdmin(CreateUserByAdminRequest $request)
    {
        
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
                        // Log the query
                        $query = Auth::user()->watchlistedUsers()->where('user_id', $user_id);
                        Log::info('Watchlist Query: ' . $query->toSql(), $query->getBindings());

                        // Execute the query and log the result
                        $isInWatchlist = $query->exists();
                        Log::info('Is in Watchlist: ' . ($isInWatchlist ? 'Yes' : 'No'));
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

    public function updateProfile(UpdateProfileRequest $request, $user_id)
{   
    $user = User::findOrFail($user_id);
    $this->authorize('update', $user);

    Log::info('Incoming request data', $request->all());

    // Convert is_public to boolean
    $request->merge([
        'is_public' => $request->is_public === 'public' ? true : false,
    ]);

    // Validate input
    $validatedData = $request->validated();
    
    Log::info('Validation successful', $validatedData);

    // Update user information
    $user->name = $request->name;
    $user->username = $request->username;
    $user->age = $request->age;
    $user->bio = $request->bio;
    $user->is_public = $request->is_public;
    $user->gender = $request->gender;
    $user->country_id = $request->country_id;

    // Handle profile picture update
    if ($request->hasFile('photo_url')) {
        if ($user->photo_url) {
            $oldPhotoPath = str_replace('private/', '', $user->photo_url);
            Log::info('Old photo path: ' . $oldPhotoPath);
            Storage::disk('private')->delete($oldPhotoPath);
        }

        $path = $request->file('photo_url')->store('profile_pictures', 'private');
        Log::info('New photo path: ' . $path);
        $user->photo_url = $path;
    }

    // Save the updated user record
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
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

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

    public function banUser(BanUserRequest $request, $user_id)
    {
        if(Auth::check() && Auth::user()->isAdmin())
        {
            if(!Banlist::where('user_id', $user_id)->exists())
            {
                $validatedData = $request->validated();
                $validatedData['user_id'] = $user_id;
                $ban = Banlist::create($validatedData);
            }
        }
        return redirect()->route('profile', ['user_id' => $user_id]);
    }

    public function unbanUser($user_id)
    {
        if(Auth::check() && Auth::user()->isAdmin())
        {
            if(Banlist::where('user_id', $user_id)->exists())
            {
                Banlist::where('user_id', $user_id)->delete();
            }
        }
        return redirect()->route('profile', ['user_id' => $user_id]);
    }

    public function acceptUnbanRequest($id)
    {
        if(Auth::check() && Auth::user()->isAdmin())
        {   
            $question = Question::findOrFail($id);
            $user_email = $question->email;
            $user = User::where('email', $user_email)->first();
            if($user)
            {
                if(Banlist::where('user_id', $user->id)->exists())
                {
                    Banlist::where('user_id', $user->id)->delete();
                    $question->delete();
                }
            }
        }
        return redirect()->route('admin.page');
    }


    public function showInfluencerPage($user_id)
    {
        $user = User::findOrFail($user_id);

        if ($user->typeu !== 'INFLUENCER') {
            abort(403, 'Unauthorized action.');
        }

        // Followers by Country
        $followersByCountry = DB::table('users')
            ->join('connection', 'users.id', '=', 'connection.initiator_user_id')
            ->join('countries', 'users.country_id', '=', 'countries.id')
            ->where('connection.target_user_id', $user_id)
            ->whereIn('connection.typer', ['FOLLOW', 'FRIEND'])
            ->select(DB::raw('countries.name as country, count(*) as count'))
            ->groupBy('countries.name')
            ->orderBy('count', 'desc')
            ->get();

        // Followers by Age
        $followersByAge = DB::table('users')
            ->join('connection', 'users.id', '=', 'connection.initiator_user_id')
            ->where('connection.target_user_id', $user_id)
            ->whereIn('connection.typer', ['FOLLOW', 'FRIEND'])
            ->select(DB::raw('users.age as age, count(*) as count'))
            ->groupBy('users.age')
            ->orderBy('count', 'desc')
            ->get();

        // Followers by Gender
        $followersByGender = DB::table('users')
            ->join('connection', 'users.id', '=', 'connection.initiator_user_id')
            ->where('connection.target_user_id', $user_id)
            ->whereIn('connection.typer', ['FOLLOW', 'FRIEND'])
            ->select(DB::raw('users.gender as gender, count(*) as count'))
            ->groupBy('users.gender')
            ->orderBy('count', 'desc')
            ->get();

        // Posts Statistics
        $posts = Post::where('owner_id', $user_id)->get();
        $postLikes = $posts->mapWithKeys(function ($post) {
            return [$post->id => $post->getNumberOfLikes()];
        });
        $postComments = $posts->mapWithKeys(function ($post) {
            return [$post->id => $post->getNumberOfComments()];
        });

        // Categories used in posts
        $categoriesUsed = DB::table('postcategory')
            ->join('category', 'postcategory.category_id', '=', 'category.id')
            ->join('post', 'postcategory.post_id', '=', 'post.id')
            ->where('post.owner_id', $user_id)
            ->select(DB::raw('category.name as category, count(*) as count'))
            ->groupBy('category.name')
            ->orderBy('count', 'desc')
            ->get();

        // Create Charts
        $followersByCountryChart = Chartjs::build()
            ->name('followersByCountryChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 400])
            ->labels($followersByCountry->pluck('country')->toArray())
            ->datasets([
                [
                    'label' => 'Followers by Country',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'data' => $followersByCountry->pluck('count')->toArray(),
                ],
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Country'
                        ]
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Followers'
                        ],
                        'ticks' => [
                            'beginAtZero' => true,
                            'precision' => 0
                        ]
                    ]
                ]
            ]);

        $followersByAgeChart = Chartjs::build()
            ->name('followersByAgeChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 400])
            ->labels($followersByAge->pluck('age')->toArray())
            ->datasets([
                [
                    'label' => 'Followers by Age',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'data' => $followersByAge->pluck('count')->toArray(),
                ],
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Age'
                        ]
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Followers'
                        ],
                        'ticks' => [
                            'beginAtZero' => true,
                            'precision' => 0
                        ]
                    ]
                ]
            ]);

        $followersByGenderChart = Chartjs::build()
            ->name('followersByGenderChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 400])
            ->labels($followersByGender->pluck('gender')->toArray())
            ->datasets([
                [
                    'label' => 'Followers by Gender',
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    'data' => $followersByGender->pluck('count')->toArray(),
                ],
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Followers by Gender'
                    ]
                ]
            ]);

        $postLikesChart = Chartjs::build()
            ->name('postLikesChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 400])
            ->labels($postLikes->keys()->toArray())
            ->datasets([
                [
                    'label' => 'Post Likes',
                    'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'data' => $postLikes->values()->toArray(),
                ],
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Post ID'
                        ]
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Likes'
                        ],
                        'ticks' => [
                            'beginAtZero' => true,
                            'precision' => 0
                        ]
                    ]
                ]
            ]);

        $postCommentsChart = Chartjs::build()
            ->name('postCommentsChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 400])
            ->labels($postComments->keys()->toArray())
            ->datasets([
                [
                    'label' => 'Post Comments',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'data' => $postComments->values()->toArray(),
                ],
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Post ID'
                        ]
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Comments'
                        ],
                        'ticks' => [
                            'beginAtZero' => true,
                            'precision' => 0
                        ]
                    ]
                ]
            ]);

        $categoriesUsedChart = Chartjs::build()
            ->name('categoriesUsedChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 400])
            ->labels($categoriesUsed->pluck('category')->toArray())
            ->datasets([
                [
                    'label' => 'Categories Used',
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(199, 199, 199, 0.2)',
                        'rgba(83, 102, 255, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                        'rgba(83, 102, 255, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                    ],
                    'data' => $categoriesUsed->pluck('count')->toArray(),
                ],
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Categories Used in Posts'
                    ]
                ]
            ]);

        return view('pages.influencer', [
            'user' => $user,
            'followersByCountryChart' => $followersByCountryChart,
            'followersByAgeChart' => $followersByAgeChart,
            'followersByGenderChart' => $followersByGenderChart,
            'postLikesChart' => $postLikesChart,
            'postCommentsChart' => $postCommentsChart,
            'categoriesUsedChart' => $categoriesUsedChart,
            'followersByCountry' => $followersByCountry,
            'followersByAge' => $followersByAge,
            'followersByGender' => $followersByGender,
            'postLikes' => $postLikes,
            'postComments' => $postComments,
            'categoriesUsed' => $categoriesUsed,
        ]);
    }
}
