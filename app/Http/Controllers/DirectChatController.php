<?php

namespace App\Http\Controllers;

use App\Models\DirectChat;
use Illuminate\Http\Request;

class DirectChatController extends Controller
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
    public function show(DirectChat $directChat)
    {
        return view('pages.direct_chat', compact('directChat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DirectChat $directChat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DirectChat $directChat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DirectChat $directChat)
    {
        //
    }
}
