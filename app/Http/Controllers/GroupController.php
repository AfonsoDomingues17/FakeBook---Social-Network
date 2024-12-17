<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupParticipant;



class GroupController extends Controller
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
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'selected_users' => 'nullable|string'
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $photoUrl = $file->store('group_pictures', 'private'); // Stores in storage/app/private/group_pictures
        }

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo_url' => $photoUrl,
            'owner_id' => Auth::id()
        ]);

        // Add the owner as a group participant
        GroupParticipant::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'date_joined' => now()
        ]);

        // Add selected users as group participants
        if ($request->selected_users) {
            $selectedUsers = explode(',', $request->selected_users);
            foreach ($selectedUsers as $userId) {
                GroupParticipant::create([
                    'group_id' => $group->id,
                    'user_id' => $userId,
                    'date_joined' => now()
                ]);
            }
        }

        return redirect()->route('homepage');
    }
    public function getPhoto($group_id){
        $group = Group::find($group_id);
        if ($group->photo_url) {

            $path = storage_path('app/private/' . $group->photo_url);
    
            if (!Storage::disk('private')->exists($group->photo_url)) {
                abort(404);
            }
    
            $file = Storage::disk('private')->get($group->photo_url);
            $type = Storage::disk('private')->mimeType($group->photo_url);
    
            return Response::make($file, 200)->header("Content-Type", $type);
        } else {
            $defaultPath = storage_path('app/private/group_pictures/DEFAULT_GROUP.png');
            if (!file_exists($defaultPath)) {
                abort(404);
            }
    
            $file = file_get_contents($defaultPath);
            $type = mime_content_type($defaultPath);
            return response($file, 200)->header("Content-Type", $type);
        }


    }
    public function groupInfo($group_id){
        $group = Group::find($group_id);
        return view('partials.group_info', ['group' => $group]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $group = Group::find($id);
        return view('partials.chat', ['chat' => $group, 'type' => 'group']);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
        public function leaveGroup($group_id)
    {
        $user = Auth::user();
        $group = Group::find($group_id);

        if ($group->owner_id == $user->id) {
            // Transfer ownership to the next member
            $new_owner_id = GroupParticipant::where('group_id', $group_id)
                ->where('user_id', '!=', $user->id)
                ->orderBy('date_joined', 'asc')
                ->value('user_id');

            if ($new_owner_id) {
                $group->owner_id = $new_owner_id;
                $group->save();
            } else {
                // If no other members, delete the group
                $group->delete();
                return redirect()->route('homepage')->with('success', 'Group has been deleted.');
            }
        }

        // Remove the user from the group participants
        $group->participants()->detach($user->id);

        return redirect()->route('homepage')->with('success', 'You have left the group.');
    }

    public function deleteGroup($group_id)
    {
        $group = Group::find($group_id);

        if ($group->owner_id != Auth::id()) {
            return redirect()->back()->with('error', 'Only the group owner can delete the group.');
        }

        $group->delete();

        return redirect()->route('homepage')->with('success', 'Group has been deleted.');
    }

    public function updateGroup(Request $request, $group_id)
    {
        $group = Group::find($group_id);

        if ($group->owner_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'group_name' => 'nullable|string|max:50',
            'group_description' => 'nullable|string|max:255',
        ]);

        if ($request->has('group_name')) {
            $group->name = $request->group_name;
        }

        if ($request->has('group_description')) {
            $group->description = $request->group_description;
        }

        $group->save();

        return response()->json($group);
}
}
