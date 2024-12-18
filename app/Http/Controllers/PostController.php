<?php
// FILE: app/Http/Controllers/PostController.php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\Post;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Events\PostLike;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            // User is logged in, show posts from followed users
            $user = Auth::user();
            $blockedUserIds = $user->blockedUsers()->pluck('target_user_id')->merge($user->blockedBy()->pluck('initiator_user_id'));

            $posts = Post::whereIn('owner_id', $user->following()->pluck('target_user_id'))
                         ->whereNotIn('owner_id', $blockedUserIds)
                         ->orderBy('datecreation', 'desc')
                         ->get();
        } else {
            // User is not logged in, show public posts
            $posts = Post::where('is_public', true)
                         ->orderBy('datecreation', 'desc')
                         ->get();
        }

        return view('pages.homepage', compact('posts'));
    }

    public function getPosts(Request $request = null)
    {
        if ($request === null) {
            $type = 'public';
        }
        else{
            $type = $request->input('type');
        }
        if ($type === 'public') {
            if (auth()->check()) {
                $blockedUserIds = auth()->user()->blockedUsers()->pluck('target_user_id')->merge(auth()->user()->blockedBy()->pluck('initiator_user_id'));
                $posts = Post::where('is_public', true)
                    ->whereHas('owner', function ($query) {
                        $query->where('is_public', true);
                    })
                    ->where('owner_id', '!=', auth()->id())
                    ->whereNotIn('owner_id', $blockedUserIds)
                    ->get();
            } else {
                $posts = Post::where('is_public', true)
                    ->whereHas('owner', function ($query) {
                        $query->where('is_public', true);
                    })
                    ->get();
            }
        } elseif ($type === 'following') {
            if (auth()->check()) {
                $posts = Post::where('is_public', true)
                    ->whereIn('owner_id', auth()->user()->following()->pluck('id'))
                    ->get();
            } else {
                $posts = collect();
            }
        } else {
            $posts = collect();
        }

        return $posts;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('partials.create_post', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Log the incoming request data
        \Log::info('Incoming request data', $request->all());

        $validatedData = $request->validated();

        // Determine the post type based on whether files are uploaded
        $validatedData['typep'] = $request->hasFile('media') ? 'MEDIA' : 'TEXT';

        // Add the owner_id to the validated data
        $validatedData['owner_id'] = Auth::id();

        // Create a new post with the validated data
        $post = Post::create($validatedData);

        // Log the created post
        \Log::info('Created post', $post->toArray());

        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                // Store file in private/post_pictures
                $filePath = $file->store('private/post_pictures');
                
                // Save the file path to the database
                Media::create([
                    'photo_url' => $filePath, // Store the relative path
                    'post_id' => $post->id,
                ]);
            }
        }

        // Handle category assignment
        if ($request->filled('category')) {
            $post->categories()->attach($request->input('category'));
        }

        // Redirect to the user's profile page
        return redirect()->route('profile', ['user_id' => Auth::id()]);
    }
    /**
     * Display the specified resource.
     */
    public function show($post_id)
{
    $post = Post::findOrFail($post_id);
    $this->authorize('view', $post);
    return view('partials.post', compact('post'));
}

public function edit($post_id)
{
    $post = Post::findOrFail($post_id);
    $this->authorize('update', $post);
    return view('partials.edit_post', compact('post'));
}

public function update(UpdatePostRequest $request, $post_id)
    {
        // Log the incoming request data
        \Log::info('Incoming request data', $request->all());

        $post = Post::findOrFail($post_id);
        $this->authorize('update', $post);

        $validatedData = $request->validated();
        $validatedData['is_edited'] = true;

        // Update the post with the validated data
        $post->update($validatedData);

        // Redirect to the previous page
        return redirect()->to($request->input('previous_url'))->with('success', 'Post updated successfully.');
    }

public function destroy($post_id)
{
    $post = Post::findOrFail($post_id);
    $this->authorize('delete', $post);

    // Delete associated media files from storage
    foreach ($post->media as $media) {
        Storage::delete($media->photo_url); // Delete the file from storage
        $media->delete(); // Delete the media record
    }

    // Delete likes on the post
    $post->likedByUsers()->detach();

    // Delete associated comments and likes
    foreach ($post->comments as $comment) {
        $comment->likedByUsers()->detach();
        $comment->delete();
    }

    // Delete the post
    $post->delete();
    
    // Redirect to previous page
    return redirect()->back();
}
}