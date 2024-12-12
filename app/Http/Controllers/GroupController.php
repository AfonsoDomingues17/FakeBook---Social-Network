<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



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
        ]);
        $photoUrl = null;
        if ($request->hasFile('photo_url')) {
            
            $file = $request->file('photo_url');
            
            $photoUrl = $file->store('group_pictures', 'private'); // Stores in storage/app/public/profile_pictures
        }

        Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo_url' => $photoUrl,
            'owner_id' => Auth::id()
        ]);
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

    /**
     * Display the specified resource.
     */
    public function show($group_id)
    {
        $group = Group::find($group_id);
        return view('partials.group', ['group' => $group]);
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
}
