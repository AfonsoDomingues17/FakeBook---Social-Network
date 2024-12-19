<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupParticipant;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\User;



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
    public function createGroup(CreateGroupRequest $request)
    {
        $validated = $request->validated();

        $photoUrl = null;
        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $photoUrl = $file->store('group_pictures', 'private'); // Stores in storage/app/private/group_pictures
        }

        $group = Group::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
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
    public function groupInfo($group_id)
    {
        $group = Group::findOrFail($group_id);
        $followersNotInGroup = Auth::user()->following->filter(function($follower) use ($group) {
            return !$group->participants->contains($follower->id);
        });

    return view('partials.group_info', compact('group', 'followersNotInGroup'));
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

                // Delete the group's image if it exists
                if ($group->photo_url && Storage::disk('private')->exists($group->photo_url)) {
                    Storage::disk('private')->delete($group->photo_url);
                }
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

        // Delete the group's image if it exists
        if ($group->photo_url && Storage::disk('private')->exists($group->photo_url)) {
            Storage::disk('private')->delete($group->photo_url);
        }

        $group->delete();

        return redirect()->route('homepage')->with('success', 'Group has been deleted.');
    }

    public function updateGroup(UpdateGroupRequest $request, $group_id)
    {
        $group = Group::find($group_id);

        if ($group->owner_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validated();

        if ($request->has('group_name')) {
            $group->name = $validated['group_name'];
        }

        if ($request->has('group_description')) {
            $group->description = $validated['group_description'];
        }

        $group->save();

        return response()->json($group);
    }
    public function getMembers(Request $request, $group_id)
    {
        $user = Auth::user();        
        $group = Group::findOrFail($group_id);
        $page = $request->input('page', 1);
        $limit = 10;

        $followersNotInGroup = $user->following->filter(function($follower) use ($group) {
            return !$group->participants->contains($follower->id);
        });

        $followersNotInGroup = $followersNotInGroup->forPage($page, $limit)->values();

        return response()->json($followersNotInGroup);
    }

    public function addMember(Request $request, $group_id, $user_id)
    {
        $group = Group::findOrFail($group_id);
        $user = User::findOrFail($user_id);

        // Add the user to the group
        $group->participants()->attach($user);

        return response()->json(['message' => 'Member added successfully']);
    }
    
    public function removeMember(Request $request, $groupId)
    {
        $userId = $request->input('user_id');
        GroupParticipant::where(['group_id' => $groupId, 'user_id' => $userId])->delete();
        return response()->json(['success' => true]);
    }
}
