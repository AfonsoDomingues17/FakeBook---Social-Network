<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function showHelpPage()
    {
        return view('pages.help');
    }
    public function sendHelpForm()
    {
        return back()->with('success', 'Your message has been sent successfully!');
    }
    public function showAboutPage()
    {
        return view('pages.about');
    }
    public function showSettingsPage()
    {
        return view('pages.settings');
    }
}
