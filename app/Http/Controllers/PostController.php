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
        /*
        $posts = Post::all();
        return view('posts.index', compact('posts'));
        */
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
        $validatedData = $request->validate([
            'description' => 'string|max:1000',
            'is_public' => 'boolean',
            'typeP' => ['required', Rule::in(['TEXT', 'MEDIA'])],
            'media' => 'array|max:5',
            'media.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Add the owner_id to the validated data
        $validatedData['owner_id'] = Auth::id();

        // Create a new post with the validated data
        $post = Post::create($validatedData);

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
        return redirect()->route('posts.show', $post);
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
        $validatedData = $request->validate([
            'description' => 'string|max:1000',
            'is_public' => 'boolean',
            'typeP' => ['required', Rule::in(['TEXT', 'MEDIA'])],
            'media' => 'array|max:5',
            'media.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $validatedData['is_edited'] = true;
        // Update the post with the validated data
        $post->update($validatedData);

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

        // Redirect to the updated post's page or another appropriate page
        return redirect()->route('posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        // Redirect to the posts list or another appropriate page
        return redirect()->route('posts.index');
    }
}