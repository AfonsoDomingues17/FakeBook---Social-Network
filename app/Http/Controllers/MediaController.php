<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
    public function show(Media $media)
    {
        // Ensure the user has permission to view the media
        $this->authorize('view', $media->post);

        // Get the file path
        $filePath = $media->photo_url;

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
