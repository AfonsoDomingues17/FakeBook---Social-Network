<?php

namespace App\Http\Controllers;

use App\Models\DirectChat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectChatController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();

        // Get the IDs of users that the current user has blocked or has been blocked by
        $blockedUserIds = $user->blockedUsers()->pluck('target_user_id')->merge($user->blockedBy()->pluck('initiator_user_id'));

        // Retrieve direct chats excluding those with blocked users
        $directChats = DirectChat::where(function ($query) use ($user) {
            $query->where('user1_id', $user->id)
                  ->orWhere('user2_id', $user->id);
        })->whereNotIn('user1_id', $blockedUserIds)
          ->whereNotIn('user2_id', $blockedUserIds)
          ->get();

        return view('pages.direct_chats', compact('directChats'));
    }

    public function show($id)
    {
        $directChat = DirectChat::findOrFail($id);
        return view('partials.direct_chat', compact('directChat'));
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

        return redirect()->route('direct_chats.show', $directChat->id);
    }
}
