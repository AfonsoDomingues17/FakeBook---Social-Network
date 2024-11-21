<?php
// FILE: app/Http/Controllers/PostController.php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
            $posts = Post::whereIn('owner_id', $user->following()->pluck('target_user_id'))
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('partials.create_post');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the incoming request data
        \Log::info('Incoming request data', $request->all());

        $validatedData = $request->validate([
            'description' => 'string|max:1000',
            'is_public' => 'boolean',
            'typep' => ['required', Rule::in(['TEXT', 'MEDIA'])],
            'media' => 'array|max:5',
            'media.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Log the validated data
        \Log::info('Validated data', $validatedData);

        // Add the owner_id to the validated data
        $validatedData['owner_id'] = Auth::id();

        // Create a new post with the validated data
        $post = Post::create($validatedData);

        // Log the created post
        \Log::info('Created post', $post->toArray());

        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $filePath = $file->store('media', 'public');
                Media::create([
                    'photo_url' => $filePath,
                    'post_id' => $post->id
                ]);
            }
        }

        // Redirect to the newly created post's page or another appropriate page
        return redirect()->route('profile', ['user_id' => Auth::id()]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('partials.post', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('partials.edit_post', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Log the incoming request data
        \Log::info('Incoming request data', $request->all());

        $validatedData = $request->validate([
            'description' => 'string|max:1000',
            'is_public' => 'boolean'
        ]);
        $validatedData['is_edited'] = true;
        // Log the validated data
        \Log::info('Validated data', $validatedData);

        // Update the post with the validated data
        $post->update($validatedData);

        // Redirect to the user's profile page
        return redirect()->route('profile', ['user_id' => Auth::id()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        
        // Redirect to the posts list or another appropriate page
        return redirect()->route('profile', ['user_id' => Auth::id()]);
    }
}