<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\DirectChat;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
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
        $groupId = $request->input('group_id');
        $content = $request->input('content');
        $image = $request->file('image');

        if (empty($content) && !$image) {
            return response()->json(['error' => 'Message content or image is required.'], 422);
        }

        $message = new Message();
        $message->author_id = $user->id;
        $message->content = $content;

        if ($image) {
            $filePath = $image->store('private/chat_images');
            $message->image_url = $filePath;
        }

        if ($directChatId) {
            $message->direct_chat_id = $directChatId;
            $message->save();

            // Load the author relationship once
            $message->load('author');

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
                'message' => $message
            ]);
        } elseif ($groupId) {
            $message->group_id = $groupId;
            $message->save();

            // Load the author relationship once
            $message->load('author');

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

            $pusher->trigger('group-chat-' . $groupId, 'new-message', [
                'message' => $message
            ]);
        }

        return response()->json(['message' => $message]);
    }

    public function show($message_id)
    {
        $message = Message::findOrFail($message_id);

        // Get the file path
        $filePath = $message->image_url;

        // Check if the file exists in storage
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Serve the file as a response
        $fileContent = Storage::get($filePath);
        $mimeType = Storage::mimeType($filePath);

        return Response::make($fileContent, 200, ['Content-Type' => $mimeType]);
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
    public function destroy($message_id)
    {
        $message = Message::findOrFail($message_id);

        // Ensure the user is authorized to delete the message
        if (Auth::id() !== $message->author_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the image file if it exists
        if ($message->image_url && Storage::exists($message->image_url)) {
            Storage::delete($message->image_url);
        }

        // Broadcast the deletion event using Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        if ($message->direct_chat_id) {
            $pusher->trigger('direct-chat-' . $message->direct_chat_id, 'delete-message', [
                'message_id' => $message->id
            ]);
        } elseif ($message->group_id) {
            $pusher->trigger('group-chat-' . $message->group_id, 'delete-message', [
                'message_id' => $message->id
            ]);
        }

        // Delete the message
        $message->delete();

        return response()->json(['status' => 'Message deleted successfully.']);
    }
}