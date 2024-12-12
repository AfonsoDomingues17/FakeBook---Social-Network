<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getPostComments($post_id)
    {
        $comments = Comment::where('post_id', $post_id)->get();
        return view('partials.comment', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:1000',
            'post_id' => 'required|integer|exists:post,id',
        ]);

        $validatedData['author_id'] = auth()->id();

        $comment = Comment::create($validatedData);

        return view('partials.comment', ['comment' => $comment])->render();
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $this->authorize('update', $comment);

        $validatedData = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $validatedData['is_edited'] = true;

        $comment->update($validatedData);

        return $comment->content;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $this->authorize('delete', $comment);

        $comment->delete();

        return response('Successful Delete', 204);
    }
}
