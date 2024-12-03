<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\DirectChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class MessageController extends Controller
{
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $directChatId = $request->input('direct_chat_id');
        $content = $request->input('content');
        $image = $request->file('image');

        $message = new Message();
        $message->direct_chat_id = $directChatId;
        $message->author_id = $user->id;
        $message->content = $content;

        if ($image) {
            $path = $image->store('images', 'public');
            $message->image_url = $path;
        }

        $message->save();

        // Broadcast the message using Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        $pusher->trigger('direct-chat-' . $directChatId, 'new-message', [
            'message' => $message->load('author')
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
