<?php

namespace App\Http\Controllers;

use App\Models\DirectChat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;

class DirectChatController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();
        $directChats = DirectChat::with(['user1', 'user2', 'messages' => function($query) {
            $query->latest()->first();
        }])->where('user1_id', $user->id)->orWhere('user2_id', $user->id)->get();

        $groups = Group::with(['messages' => function($query) {
            $query->latest()->first();
        }])->whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('pages.direct_chats', compact('directChats', 'groups'));
    }

    public function show($id)
    {
        $directChat = DirectChat::findOrFail($id);
        return view('partials.chat', ['chat' => $directChat, 'type' => 'direct']);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $recipientId = $request->input('recipient_id');

        // Check if a direct chat already exists between the users
        $directChat = DirectChat::betweenUsers($user->id, $recipientId);

        // If no direct chat exists, create a new one
        if (!$directChat) {
            $directChat = DirectChat::create([
                'user1_id' => $user->id,
                'user2_id' => $recipientId
            ]);
        }

        return redirect()->route('direct_chats.index');
    }
}
