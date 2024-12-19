<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($media_id)
    {
        if ($media_id === 'default') {
            $defaultPath = storage_path('app/private/post_pictures/DEFAULT_POST.jpg');
            if (!file_exists($defaultPath)) {
                abort(404);
            }

            $file = file_get_contents($defaultPath);
            $type = mime_content_type($defaultPath);
            return response($file, 200)->header("Content-Type", $type);
        }

        $media = Media::find($media_id);

        if (!$media) {
            $defaultPath = storage_path('app/private/post_pictures/DEFAULT_POST.jpg');
            if (!file_exists($defaultPath)) {
                abort(404);
            }

            $file = file_get_contents($defaultPath);
            $type = mime_content_type($defaultPath);
            return response($file, 200)->header("Content-Type", $type);
        } else {
            // Ensure the user has permission to view the media
            $this->authorize('view', $media->post);

            // Get the file path
            $filePath = $media->photo_url;
            $path = storage_path('app/' . $filePath);
            Log::info($path);

            if (!Storage::exists($filePath)) {
                abort(404);
            }

            $file = Storage::get($filePath);
            $type = Storage::mimeType($filePath);

            return response($file, 200)->header("Content-Type", $type);
        
    }
    
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        //
    }
}
